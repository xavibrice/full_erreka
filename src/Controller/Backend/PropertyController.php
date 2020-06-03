<?php

namespace App\Controller\Backend;

use App\Entity\Photo;
use App\Entity\Property;
use App\Entity\PropertyNote;
use App\Entity\Visit;
use App\Form\PropertyNoteType;
use App\Form\PropertyType;
use App\Form\VisitType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/propiedad")
 */
class PropertyController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="property_index", methods={"GET"})
     */
    public function index(PropertyRepository $propertyRepository): Response
    {
        return $this->render('backend/property/index.html.twig', [
            'properties' => $propertyRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/nueva", name="property_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $lastReference = $em->getRepository(Property::class)->findOneBy([], ['id' => 'desc']);

        if ($lastReference) {
            $newReference = $lastReference->getReference() + 1;
        } else {
            $newReference = 1;
        }

        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $property->setReference($newReference);
            $images = $request->files->get("property")["photos"];
            foreach ($images as $image) {
                $photo = new Photo();
                $photo->setPhotoFile($image);
                $property->addPhoto($photo);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($property);
            $entityManager->flush();

            return $this->redirectToRoute('property_show', [
                'id' => $property->getId(),
            ]);
        }

        return $this->render('backend/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="property_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Property $property): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $propertyNotes = $entityManager->getRepository(PropertyNote::class)->findBy(['property' => $property->getId()], ['id' => 'DESC']);

        $propertyNote = new PropertyNote();
        $propertyNote->setProperty($property);
        $form = $this->createForm(PropertyNoteType::class, $propertyNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($propertyNote);
            $entityManager->flush();

            $this->addFlash('success', 'Nota de propiedad creada correctamente');

            return $this->redirectToRoute('property_show', [
                'id' => $property->getId(),
            ]);
        }

        return $this->render('backend/property/show.html.twig', [
            'property' => $property,
            'propertyNotes' => $propertyNotes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/visita/{id}", name="property_visit", methods={"GET", "POST"})
     */
    public function visit(Request $request, Property $property): Response
    {

        $visit = new Visit();
        $form = $this->createForm(VisitType::class, $visit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visit->setProperty($property);
            $this->entityManager->persist($visit);
            $this->entityManager->flush();

            $this->addFlash('success', 'Visita creada correctamente');

            return $this->redirectToRoute('property_visit', [
                'id' => $property->getId()
            ]);
        }

        return $this->render('backend/property/visit.html.twig', [
            'property' => $property,
            'visits' => $this->entityManager->getRepository(Visit::class)->findBy([], ['id' => 'DESC']),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editar", name="property_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Property $property): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $request->files->get("property")["photos"];
            foreach ($images as $key => $image) {
                $photo = new Photo();
                $photo->setPhotoFile($image);
                $property->addPhoto($photo);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('property_show', [
                'id' => $property->getId(),
            ]);
        }

        return $this->render('backend/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/redorder", name="property_photo_reorder")
     */
    public function reorderPhoto(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        $orderedIds = json_decode($request->getContent(), true);

        $orderedIds = array_flip($orderedIds);
        foreach ($property->getPhotos() as $key => $image) {
            $image->setPosition($orderedIds[$image->getId()]);
        }
        $entityManager->flush();

        return new JsonResponse(
            $property->getPhotos(),
            200,
            []
        );

    }

    /**
     * @Route("/{id}", name="property_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Property $property): Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($property);
            $entityManager->flush();
        }

        return $this->redirectToRoute('property_index');
    }

    /**
     * @Route("/eliminar/image/{id}", name="property_image_delete")
     */
    public function deleteImage(Request $request, Photo $photo): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
        //}

        return $this->redirectToRoute('property_show', [
            'id' => $photo->getProperty()->getId(),
        ]);
    }


}
