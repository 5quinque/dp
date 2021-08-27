<?php

namespace App\EventListener;

use App\Entity\Video;
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

        $video = new Video();
        $video->setFilename($file->getFilename());
        $video->setMimeType($file->getMimeType());
        $video->setSize($file->getSize());
        $video->setCreated(new \DateTime());
        
        $this->om->persist($video);
        $this->om->flush();

        $response = $event->getResponse();
        $response['success'] = true;
        $response['video_id'] = $video->getId();

        return $response;
    }
}
