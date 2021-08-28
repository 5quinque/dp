<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(Request $request): Response
    {
        $post = new Post();
        $post->setCreated(new \DateTime());

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            
            foreach ($post->getVideos() as $video) {
                $video->setPost($post);
            }

            $entityManager->flush();

            return $this->redirectToRoute('get_uploads');
        }

        return $this->render('upload/index.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/getUploads", name="get_uploads")
     */
    public function getUploads(PostRepository $pr): Response
    {
        $posts = $pr->findAll();

        dump($posts);

        
        return $this->render('upload/all.html.twig', [
            'posts' => $posts,
        ]);
    }
}
