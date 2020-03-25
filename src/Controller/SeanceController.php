<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Seance;
use App\Form\SeanceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/seance")
 */
class SeanceController extends AbstractController
{

    /**
     * @Route("/", name="seance_index", methods={"GET", "POST"})
     */
    public function showMine(): Response
    {
        // Récupère l'utilisateur en session
        $utilisateur = $this->getUser();

        $SeanceRepository = $this->getDoctrine()->getRepository(Seance::class);
        $listeSeances = $SeanceRepository->findByUser($utilisateur);

        return $this->render('seance/index.html.twig', [
            'listeSeances' => $listeSeances,
        ]);
    }

    /**
     * @Route("/new", name="seance_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $UtilisateurManager = $this->getDoctrine()->getRepository(Utilisateur::class);
        $user = $UtilisateurManager->find($this->getUser());
        $estAbonne = false;

        foreach($user->getAbonnements() as $abo) {
            if ($abo->getEtat() == 1) {
                $estAbonne = true;
            }
        }
        if ($estAbonne) {
            $seance = new Seance();
            $seance->setDate(new \DateTime('now'));
            // Récupère l'utilisateur en session
            $utilisateur = $this->getUser();
            $seance->setUtilisateur($utilisateur);
            $form = $this->createForm(SeanceType::class, $seance);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($seance);
                $entityManager->flush();

                return $this->redirectToRoute('seance_index');
            }

            return $this->render('seance/new.html.twig', [
                'seance' => $seance,
                'form' => $form->createView(),
            ]);
        }
        else {
            // Il faut souscrire une offre !
            return $this->redirectToRoute('accueil');
        }

    }

    /**
     * @Route("/{id}", name="seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        $SeanceRepository = $this->getDoctrine()->getRepository(Seance::class);
        $seance = $SeanceRepository->find($seance->getId());

        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="seance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Seance $seance): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('seance_index');
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="seance_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Seance $seance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('seance_index');
    }
}