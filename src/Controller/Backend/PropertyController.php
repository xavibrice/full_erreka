<?php

namespace App\Controller\Backend;

use App\Entity\Photo;
use App\Entity\Property;
use App\Form\PropertyType;
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
     * @Route("/{id}", name="property_show", methods={"GET"})
     */
    public function show(Property $property): Response
    {
        return $this->render('backend/property/show.html.twig', [
            'property' => $property,
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
     * @Route("/{id}/fotos", name="property_photo_upload")
     */
    public function uploadPhoto(Request $request, Property $property): Response
    {
        dd($request->files->get('photos'));
        dd('ok');
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
}
