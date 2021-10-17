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
     * @Route("/view/{title}", name="post")
     */
    public function getUploads(Post $post): Response
    {;
        return $this->render('view/post.html.twig', [
            'post' => $post,
        ]);
    }

}
