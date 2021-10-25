<?php

namespace App\EventListener;

use App\Entity\Media;
use Doctrine\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
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

        return $response;
    }
}
