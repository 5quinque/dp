<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Tag;
use App\Form\Type\PostType;
use App\Form\Type\TagType;
use App\Repository\TagRepository;
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            
            foreach ($post->getMedia() as $media) {
                $media->setPost($post);
            }

            $entityManager->flush();

            return $this->redirectToRoute('view_post', ['post' => $post->getId()]);
        }

        return $this->render('upload/index.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/tag", name="tag")
     */
    public function test(Request $request): Response
    {
        $tag = new Tag();
        $tag->setCreated(new \DateTime());

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            
            $entityManager->flush();

            // return $this->redirectToRoute('view_post', ['post' => $post->getId()]);
        }

        return $this->render('upload/tag.html.twig', [
            'tag_form' => $form->createView(),
        ]);
    }
}
