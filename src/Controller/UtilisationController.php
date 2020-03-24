<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entity\Utilisation;
use App\Form\UtilisationType;
use App\Repository\UtilisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisation")
 */
class UtilisationController extends AbstractController
{
    /**
     * @Route("/", name="utilisation_index", methods={"GET"})
     */
    public function index(UtilisationRepository $utilisationRepository): Response
    {
        return $this->render('utilisation/index.html.twig', [
            'utilisations' => $utilisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="utilisation_new", methods={"GET","POST"})
     */
    public function new(Request $request, Seance $seance): Response
    {
        $utilisation = new Utilisation();
        if (isset($seance))
        {
            $utilisation->setSeance($seance);
            $nbUtilisations = 0;
            foreach ($seance->getUtilisations() as $utilisationT) {
                $nbUtilisations = $nbUtilisations+ $utilisationT->getOrdre();
            }
            $utilisation->setOrdre($nbUtilisations +1); // par défaut, on met l'ordre n+1 par rapport à l'ordre le plus grand.
        }

        $form = $this->createForm(UtilisationType::class, $utilisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisation);
            $entityManager->flush();

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('utilisation/new.html.twig', [
            'utilisation' => $utilisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisation_show", methods={"GET"})
     */
    public function show(Utilisation $utilisation): Response
    {
        return $this->render('utilisation/show.html.twig', [
            'utilisation' => $utilisation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="utilisation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Utilisation $utilisation): Response
    {
        $form = $this->createForm(UtilisationType::class, $utilisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisation_index');
        }

        return $this->render('utilisation/edit.html.twig', [
            'utilisation' => $utilisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Utilisation $utilisation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seance_index');
    }
}
