<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Post;
use App\Message\FileMessage;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="view_")
 */
class ViewController extends AbstractController
{
    /**
     * @Route("/latest", name="index")
     */
    public function posts(PostRepository $pr): Response
    {
        $posts = $pr->findAll();

        return $this->render('view/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/view/{post}", name="post")
     */
    public function getUploads(Post $post): Response
    {
        return $this->render('view/post.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/dispatch/{file}", name="post_dispatch")
     */
    public function dispatch(Media $file, MessageBusInterface $bus): Response
    {
        $bus->dispatch(new FileMessage($file->getId()));
        
        return new Response("Sent dispatch");
    }
}
