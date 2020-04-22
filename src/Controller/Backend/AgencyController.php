<?php

namespace App\Controller\Backend;

use App\Entity\Agency;
use App\Form\AgencyType;
use App\Repository\AgencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/agencia")
 */
class AgencyController extends AbstractController
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="agency_index", methods={"GET"})
     */
    public function index(AgencyRepository $agencyRepository): Response
    {
        return $this->render('backend/agency/index.html.twig', [
            'agencies' => $agencyRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/nueva", name="agency_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $agency = new Agency();
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agency->setSlug($this->slugger->slug($agency->getName())->lower());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agency);
            $entityManager->flush();

            return $this->redirectToRoute('agency_index');
        }

        return $this->render('backend/agency/new.html.twig', [
            'agency' => $agency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="agency_show", methods={"GET"})
     */
    public function show(Agency $agency): Response
    {
        return $this->render('backend/agency/show.html.twig', [
            'agency' => $agency,
        ]);
    }

    /**
     * @Route("/{slug}/editar", name="agency_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Agency $agency): Response
    {
        $form = $this->createForm(AgencyType::class, $agency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agency->setSlug($this->slugger->slug($agency->getName())->lower());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('agency_index');
        }

        return $this->render('backend/agency/edit.html.twig', [
            'agency' => $agency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agency_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Agency $agency): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agency->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($agency);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agency_index');
    }
}
