<?php

namespace App\MessageHandler;

use App\Message\FileMessage;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FileMessageHandler implements MessageHandlerInterface
{
    private $mediaRepository;
    private $mediaFilesystem;
    // private $om;
    private $entityManager;
    protected $parameterBag;

    public function __construct(
        MediaRepository $mediaRepository,
        EntityManagerInterface $entityManager,
        FilesystemInterface $mediaFilesystem,
        ParameterBagInterface $parameterBag
        // EntityManager $em
    ) {
        $this->mediaRepository = $mediaRepository;
        $this->mediaFilesystem = $mediaFilesystem;
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
    }

    public function __invoke(FileMessage $fileMessage)
    {
        $file = $this->mediaRepository->find($fileMessage->getFileId());

        $projectDir = $this->parameterBag->get('kernel.project_dir');

        $this->mediaFilesystem->writeStream(
            $file->getFilename(),
            fopen("{$projectDir}/var/storage/default/{$file->getFilename()}", "r")
        );


        $file->setFilesystem("media");

        $this->entityManager->persist($file);
        $this->entityManager->flush();
    }
}
