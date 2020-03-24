<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Paiement;
use App\Form\Paiement1Type;
use App\Repository\PaiementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paiementAdmin")
 */
class PaiementAdminController extends AbstractController
{
    /**
     * @Route("/", name="paiementAdmin_index")
     */
    public function index( ): Response
    { //Entete avant modification public function index(PaiementRepository $paiementRepository): Response
        /*
         * Lignes générées :   j'ai supprimé les repository... donc ca ne marche plus
         return $this->render('paiementAdmin/index.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);*/

        $paiements = $this->getDoctrine()
            ->getRepository(Paiement::class)
            ->findAll();

        return $this->render('paiementAdmin/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }

    /**
     * @Route("/new", name="paiementAdmin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(Paiement1Type::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('paiementAdmin_index');
        }

        return $this->render('paiementAdmin/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paiementAdmin_show", methods={"GET"})
     */
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiementAdmin/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="paiementAdmin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Paiement $paiement): Response
    {
        $form = $this->createForm(Paiement1Type::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('paiementAdmin_index');
        }

        return $this->render('paiementAdmin/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paiementAdmin_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Paiement $paiement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('paiementAdmin_index');
    }
}
