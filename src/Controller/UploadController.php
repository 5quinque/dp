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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload")
     */
    public function index(
        Request $request,
        TagRepository $tr
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

        // Get all the tags in JSON format
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $tagsJson = $serializer->serialize($tr->findAll(), 'json');

        return $this->render('upload/index.html.twig', [
            'post_form' => $form->createView(),
            'tags' => $tagsJson
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
