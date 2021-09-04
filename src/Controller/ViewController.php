<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use League\Flysystem\FilesystemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view_")
 */
class ViewController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function posts(PostRepository $pr): Response
    {
        $posts = $pr->findAll();


        return $this->render('view/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/{post}", name="post")
     */
    public function getUploads(Post $post, FilesystemInterface $mediaFilesystem): Response
    {
        foreach ($post->getMedia() as $media) {
            $bucket = $mediaFilesystem->getAdapter()->getBucket();
            $objectUrl = $mediaFilesystem->getAdapter()->getClient()->getObjectUrl($bucket, $media->getFilename());
            $media->objectUrl = $objectUrl;
            // dump($media);
        }

        return $this->render('view/post.html.twig', [
            'post' => $post,
        ]);
    }
}
