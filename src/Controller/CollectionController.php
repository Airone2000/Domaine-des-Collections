<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Form\CollectionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/collections", name="collection_")
 */
class CollectionController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(CollectionType::class, null, [
            'label' => false,
            'validation_groups' => 'Collection:Create'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var Collection $collection */
            $collection = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();
            $this->addFlash('createCollectionSuccess', '');
            return $this->redirectToRoute('collection_home_of_collection', [
                'id' => $collection->getId()
            ]);
        }
        return $this->render('collection/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="home_of_collection")
     */
    public function homeOfCollection(Collection $collection): Response
    {
        return $this->render('collection/home-of-collection.html.twig', [
            'collection' => $collection
        ]);
    }
}