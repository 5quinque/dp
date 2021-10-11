<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Form\Type\CollectionType;
use App\Repository\CollectionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route("/api",name="api_")
 */
class CollectionsApiController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/collections")
     *
     * @return Response
     */
    public function getCollections(CollectionRepository $tr): Response
    {
        $view = $this->view($tr->findCollectionSansPost(), 200);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/new_collection")
     *
     * @return Response
     */
    public function postCollection(Request $request): Response
    {
        $collection = new Collection();
        $collection->setCreated(new \DateTime());

        $form = $this->createForm(CollectionType::class, $collection);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($collection);
            $entityManager->flush();

            // Generate new CSRF
            $tokenProvider = $this->container->get('security.csrf.token_manager');
            $token = $tokenProvider->getToken("collection");
            $tokenValue = $token->getValue();

            return $this->handleView(
                $this->view(
                    [
                        'status'=>'ok',
                        'id' => $collection->getId(),
                        'csrf' => $tokenValue
                    ],
                    Response::HTTP_CREATED
                )
            );
        } else {
            return $this->handleView(
                $this->view(
                    [
                        'status'=>'ok',
                        'message' => 'an error occured'
                    ],
                    Response::HTTP_BAD_REQUEST
                )
            );
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}
