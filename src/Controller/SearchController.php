<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ACSEO\TypesenseBundle\Finder\TypesenseQuery;

class SearchController extends AbstractController
{

    private $postFinder;

    public function __construct($postFinder)
    {
        $this->postFinder = $postFinder;
    }

    /**
     * @Route("/search/{term}/{page<\d+>?1}", name="search")
     */
    public function search(string $term, int $page, PostRepository $pr): Response
    {
        // return $this->render('ac.html.twig');
        $query = new TypesenseQuery($term, 'title');

        $results = $this->postFinder->query($query)->getResults();

        return $this->render('home_page/index.html.twig', [
            'paginator' => $pr->search($results, $page),
        ]);
    }
}
