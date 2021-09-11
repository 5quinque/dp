<?php

namespace App\MessageHandler;

use App\Entity\Media;
use App\Message\FileMessage;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Envelope;

class FileMessageHandler implements MessageHandlerInterface
{
    private $logger;
    private $mediaRepository;
    private $localFilesystem;
    private $mediaFilesystem;
    private $entityManager;
    private $bus;

    public function __construct(
        LoggerInterface $logger,
        MediaRepository $mediaRepository,
        EntityManagerInterface $entityManager,
        FilesystemInterface $localFilesystem,
        FilesystemInterface $mediaFilesystem,
        MessageBusInterface $bus
    ) {
        $this->logger = $logger;
        $this->mediaRepository = $mediaRepository;
        $this->mediaFilesystem = $mediaFilesystem;
        $this->localFilesystem = $localFilesystem;
        $this->entityManager = $entityManager;
        $this->bus = $bus;
    }

    public function __invoke(FileMessage $fileMessage)
    {
        $file = $this->mediaRepository->find($fileMessage->getFileId());

        if (!$file->getProcessed()) {
            $this->logger->info("File ID <{$fileMessage->getFileId()}> not processed, Re-dispatching... back in 60");

            $this->bus->dispatch(new FileMessage($fileMessage->getFileId()), [
                new DelayStamp(60000),
            ]);

            return false;
        }

        $remoteTransferSuccess = $this->mediaFilesystem->writeStream(
            $file->getFilename(),
            $this->localFilesystem->readStream($file->getFilename()),
            ['visibility' => 'public'] // Bucket has to be set to public
        );

        if ($remoteTransferSuccess) {
            $this->localFilesystem->delete($file->getFilename());
            $this->updateFileUrl($file);
            $this->entityManager->persist($file);
            $this->entityManager->flush();
        } else {
            // [TODO]: Handle remote upload failure..
        }
    }

    public function updateFileUrl(Media $file)
    {
        // $bucket = $this->mediaFilesystem->getAdapter()->getBucket();
        // $file->setObjectUrl(
        //     $this->mediaFilesystem
        //         ->getAdapter()
        //         ->getClient()
        //         ->getObjectUrl($bucket, $file->getFilename())
        // );
        $file->setObjectUrl("{$_SERVER['S3_ROOTURL']}{$file->getFilename()}");
        $file->setFilesystem("media");
    }
}
