<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="homepage_")
 */
class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $pr): Response
    {
        $posts = $pr->findAll();

        return $this->render('home_page/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
