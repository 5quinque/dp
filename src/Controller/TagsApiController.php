<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\Type\TagType;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route("/api",name="api_")
 */
class TagsApiController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/tags")
     *
     * @return Response
     */
    public function getTags(TagRepository $tr): Response
    {
        $view = $this->view($tr->findTagSansPost(), 200);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/new_tag")
     *
     * @return Response
     */
    public function postTag(Request $request): Response
    {
        $tag = new Tag();
        $tag->setCreated(new \DateTime());

        $form = $this->createForm(TagType::class, $tag);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->handleView(
                $this->view(
                    [
                        'status'=>'ok',
                        'id' => $tag->getId()
                    ],
                    Response::HTTP_CREATED
                )
            );
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}
