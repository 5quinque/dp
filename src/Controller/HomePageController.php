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
     * @Route("/{page<\d+>?1}", name="index")
     */
    public function index(int $page, PostRepository $pr): Response
    {
        return $this->render('home_page/index.html.twig', [
            'paginator' => $pr->findLatest($page),
        ]);
    }
}
