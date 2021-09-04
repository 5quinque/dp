<?php

namespace App\MessageHandler;

use App\Message\FileMessage;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FileMessageHandler implements MessageHandlerInterface
{
    private $mediaRepository;
    private $localFilesystem;
    private $mediaFilesystem;
    private $entityManager;

    public function __construct(
        MediaRepository $mediaRepository,
        EntityManagerInterface $entityManager,
        FilesystemInterface $localFilesystem,
        FilesystemInterface $mediaFilesystem
    ) {
        $this->mediaRepository = $mediaRepository;
        $this->mediaFilesystem = $mediaFilesystem;
        $this->localFilesystem = $localFilesystem;
        $this->entityManager = $entityManager;
    }

    public function __invoke(FileMessage $fileMessage)
    {
        $file = $this->mediaRepository->find($fileMessage->getFileId());

        $remoteTransferSuccess = $this->mediaFilesystem->writeStream(
            $file->getFilename(),
            $this->localFilesystem->readStream($file->getFilename())
        );

        if ($remoteTransferSuccess) {
            $this->localFilesystem->delete($file->getFilename());

            $file->setFilesystem("media");
            $this->entityManager->persist($file);
            $this->entityManager->flush();
        } else {
            // [TODO]: Handle remote upload failure..
        }
    }
}
