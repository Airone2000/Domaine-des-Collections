<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\FormComponent;
use App\Entity\Thing;
use App\Entity\Value;
use App\Enum\Device;
use App\Enum\PublicationStatus;
use App\Form\CollectionType;
use App\Form\ThingType;
use App\Repository\ValueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @Route("/{id}/insert-thing", name="insert_thing")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function insertThing(Collection $collection): Response
    {
        $thing = new Thing();
        $thing
            ->setCollection($collection)
        ;
        $em = $this->getDoctrine()->getManager();
        $em->persist($thing);
        $em->flush();
        $this->addFlash('insertThingSuccess', 'Nouvel objet créé');
        return $this->redirectToRoute('collection_edit_thing', [
            'collectionId' => $collection->getId(),
            'thingId' => $thing->getId(),
        ]);
    }

    /**
     * @Route("/{collectionId}/{thingId}/edit", name="edit_thing")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Entity(name="collection", expr="repository.find(collectionId)")
     * @Entity(name="thing", expr="repository.findOneBy({'id':thingId, 'collection':collectionId})")
     */
    public function editThing(Collection $collection, Thing $thing, Request $request, ValueRepository $valueRepository): Response
    {
        // Set to draft so that the user is free to make any changes on values
        // without causing weird side effects.
        // Maybe I should display a warning before editing : beware, this thing
        // is about to be turned into a draft and won't be available until you
        // republish it. Are you sure you want to continue ?
        // Here I reset to DRAFT because clicking the back button in the browser after a redirect
        // serves the page from cache and thus the thing remains PUBLISHED instead of being DRAFT.
        $thing->setPublicationStatus(PublicationStatus::DRAFT);
        $this->getDoctrine()->getManager()->flush();

        if ($request->isXmlHttpRequest() && $request->isMethod('PUT')) {

            $response = new JsonResponse();
            $em = $this->getDoctrine()->getManager();

            // Payload
            $requestData = json_decode($request->getContent(), true);
            $requestData ??= [];
            $requestData['object'] ??= 'undefined';
            $requestData['data'] ??= [];

            // Update object from payload
            switch($requestData['object']) {
                case 'thing':
                    $form = $this->createForm(ThingType::class, $thing);
                    $form->submit($requestData['data'], true);
                    if ($form->isValid()) {
                        $em->flush();
                        return $response;
                    } else {
                        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                        return $response;
                    }
                    break;
                case 'value':
                    $formComponentID = key($requestData['data']);
                    $valueValue = current($requestData['data']);
                    $formComponent = $collection->getForm()->getFormComponents()->filter(fn(FormComponent $formComponent) => (string) $formComponent->getId() === $formComponentID)->first();
                    if ($formComponent instanceof FormComponent) {
                        $value = new Value();
                        $value
                            ->setThing($thing)
                            ->setFormComponent($formComponent)
                            ->setValue($valueValue)
                        ;

                        if ($value->isValid($violations)) {
                            $valueRepository->deleteByThingAndFormComponent($thing, $formComponent);
                            $em->persist($value);
                            $em->flush();;
                            return $response; // Okay
                        } else {
                            return $response->setData($violations)->setStatusCode(Response::HTTP_BAD_REQUEST);
                        }
                    }
                    break;
            }

            throw new BadRequestHttpException();
        }

        $getValueByFormComponent = [];
        /* @var Value $value */
        foreach ($thing->getValues() as $value) {
            $getValueByFormComponent[(string) $value->getFormComponent()->getId()] = $value;
        }

        return $this->render('collection/edit-thing.html.twig', [
            'collection' => $collection,
            'thing' => $thing,
            'device' => Device::DESKTOP,
            'getValueByFormComponent' => $getValueByFormComponent,
        ]);
    }

    /**
     * @Route("/{collectionId}/{thingId}/toggle-publish", name="toggle_publish_thing", condition="request.isXmlHttpRequest()")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Entity(name="collection", expr="repository.find(collectionId)")
     * @Entity(name="thing", expr="repository.findOneBy({'id':thingId, 'collection':collectionId})")
     */
    public function togglePublishThing(Collection $collection, Thing $thing, Request $request): JsonResponse
    {
        $response = new JsonResponse(null, Response::HTTP_OK);
        $withRedirectLink = true;

        if ($thing->getPublicationStatus() !== PublicationStatus::PUBLISHED) {
            if ($thing->isValid(false, $violations)) {
                $thing->setPublicationStatus(PublicationStatus::PUBLISHED);
            } else {
                $response->setStatusCode(Response::HTTP_FORBIDDEN);
                $responseData['violations'] = $violations;
                $withRedirectLink = false;
            }
        } else {
            $thing->setPublicationStatus(PublicationStatus::OFFLINE);
        }

        // save new publication status
        $this->getDoctrine()->getManager()->flush();

        // Return some data
        $responseData['publicationStatus'] = $thing->getPublicationStatus();
        if ($withRedirectLink) {
            $responseData['redirectTo'] = $this->generateUrl('collection_show_thing', [
                'collectionId' => $collection->getId(),
                'thingId' => $thing->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        $response->setData($responseData);
        return $response;
    }

    /**
     * @Route("/{collectionId}/{thingId}", name="show_thing")
     * @Entity(name="collection", expr="repository.find(collectionId)")
     * @Entity(name="thing", expr="repository.findOneBy({'id':thingId, 'collection':collectionId})")
     */
    public function showThing(Collection $collection, Thing $thing): Response
    {
        return $this->render('collection/show-thing.html.twig', [
            'collection' => $collection,
            'thing' => $thing,
        ]);
    }
}