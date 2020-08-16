<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Repository\AgentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/agente")
 */
class AgentController extends AbstractController
{
    private $passwordEncoder;
    private $slugger;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    /**
     * @Route("/", name="agent_index", methods={"GET"})
     */
    public function index(AgentRepository $agentRepository): Response
    {
        return $this->render('agent/index.html.twig', [
            'agents' => $agentRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/nuevo", name="agent_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $agent = new Agent();
        $agent->setEnabled(true);
        $agent->setCreatedAt(new \DateTime());
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agent->setSlug($this->slugger->slug($agent->getUsername())->lower());
            $agent->setPassword($this->passwordEncoder->encodePassword($agent, $form->get('plainPassword')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($agent);
            $entityManager->flush();

            return $this->redirectToRoute('agent_index');
        }

        return $this->render('agent/new.html.twig', [
            'agent' => $agent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="agent_show", methods={"GET"})
     */
    public function show(Agent $agent): Response
    {
        return $this->render('agent/show.html.twig', [
            'agent' => $agent,
        ]);
    }

    /**
     * @Route("/{slug}/editar", name="agent_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Agent $agent): Response
    {
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty(trim($form->get('plainPassword')->getData()))) {
                $agent->setPassword($this->passwordEncoder->encodePassword($agent, $form->get('plainPassword')->getData()));
            }
            $agent->setSlug($this->slugger->slug($agent->getUsername())->lower());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Agente editado correctamente.');

            return $this->redirectToRoute('agent_index');
        }

        return $this->render('agent/edit.html.twig', [
            'agent' => $agent,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="agent_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Agent $agent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($agent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('agent_index');
    }
}
