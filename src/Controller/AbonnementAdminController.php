<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\Abonnement1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/abonnementAdmin")
 */
class AbonnementAdminController extends AbstractController
{
    /**
     * @Route("/", name="abonnementAdmin_index", methods={"GET"})
     */
    public function index(): Response
    {
        $abonnements = $this->getDoctrine()
            ->getRepository(Abonnement::class)
            ->findAll();

        return $this->render('abonnementAdmin/index.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }

    /**
     * @Route("/new", name="abonnementAdmin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(Abonnement1Type::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($abonnement);
            $entityManager->flush();

            return $this->redirectToRoute('abonnementAdmin_index');
        }

        return $this->render('abonnementAdmin/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="abonnementAdmin_show", methods={"GET"})
     */
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnementAdmin/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="abonnementAdmin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Abonnement $abonnement): Response
    {
        $form = $this->createForm(Abonnement1Type::class, $abonnement);
        $form->handleRequest($request);
        dump($abonnement);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('abonnementAdmin_index');
        }

        return $this->render('abonnementAdmin/edit.html.twig', [
            'abonnementAdmin' => $abonnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="abonnementAdmin_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Abonnement $abonnement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('abonnementAdmin_index');
    }
}
