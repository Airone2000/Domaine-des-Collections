<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\FormComponent;
use App\Entity\Thing;
use App\Entity\Value;
use App\Enum\Device;
use App\Form\CollectionType;
use App\Form\ThingType;
use App\Repository\ValueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @Route("/{collectionId}/{thingId}", name="edit_thing")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Entity(name="collection", expr="repository.find(collectionId)")
     * @Entity(name="thing", expr="repository.findOneBy({'id':thingId, 'collection':collectionId})")
     */
    public function editThing(Collection $collection, Thing $thing, Request $request, ValueRepository $valueRepository): Response
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('PUT')) {

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
                        return new JsonResponse([]); // Okay
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

                        if ($value->isValid()) {
                            $valueRepository->deleteByThingAndFormComponent($thing, $formComponent);
                            $em->persist($value);
                            $em->flush();;
                            return new JsonResponse([]); // Okay
                        }
                    }
                    break;
            }

            throw new BadRequestHttpException();
        }

        $getValueByFormComponent = [];
        /* @var Value $value */
        foreach ($thing->getValues() as $value) {
            $getValueByFormComponent[(string) $value->getFormComponent()->getId()] = $value->getValue();
        }

        return $this->render('collection/edit-thing.html.twig', [
            'collection' => $collection,
            'thing' => $thing,
            'device' => Device::DESKTOP,
            'getValueByFormComponent' => $getValueByFormComponent,
        ]);
    }
}