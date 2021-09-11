<?php

namespace App\EventListener;

use App\Entity\Media;
use App\Message\FileMessage;
use Doctrine\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;
    private $bus;

    public function __construct(
        ObjectManager $om,
        MessageBusInterface $bus
    ) {
        $this->om = $om;
        $this->bus = $bus;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $file = $event->getFile();

        // https://github.com/symfony/symfony/blob/5.4/src/Symfony/Component/HttpFoundation/File/UploadedFile.php#L81
        $originalFilename = $event
                            ->getRequest()
                            ->files
                            ->get('file')
                            ->getClientOriginalName();

        $media = new Media();
        $media->setFilename($file->getPath());
        $media->setOriginalFilename($originalFilename);
        $media->setMimeType($file->getMimeType());
        $media->setSize($file->getSize());
        $media->setCreated(new \DateTime());
        $media->setFilesystem("local");
        $media->setProcessed(false);
        
        $this->om->persist($media);
        $this->om->flush();

        $response = $event->getResponse();
        $response['media_id'] = $media->getId();
        $response['media_filename'] = $media->getFilename();
        $response['media_original_filename'] = $media->getFilename();

        $this->bus->dispatch(new FileMessage($media->getId()));

        return $response;
    }
}
