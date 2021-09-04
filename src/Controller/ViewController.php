<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
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
    public function getUploads(Post $post): Response
    {
        return $this->render('view/post.html.twig', [
            'post' => $post,
        ]);
    }
}
