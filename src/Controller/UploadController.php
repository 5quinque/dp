<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(
        Request $request
    ): Response {
        $post = new Post();
        $post->setCreated(new \DateTime());

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            foreach ($post->getMedia() as $media) {
                $media->setPost($post);
            }
            foreach ($post->getCollections() as $collection) {
                $collection->addPost($post);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);

            $entityManager->flush();

            return $this->redirectToRoute('view_post', ['post' => $post->getId()]);
        }

        return $this->render('upload/index.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }
}
