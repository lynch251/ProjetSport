<?php

namespace App\Controller;

use App\Entity\RoleUtilisateur;
use App\Form\RoleUtilisateurType;
use App\Repository\RoleUtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/role/utilisateur")
 */
class RoleUtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="role_utilisateur_index", methods={"GET"})
     */
    public function index(RoleUtilisateurRepository $roleUtilisateurRepository): Response
    {
        return $this->render('role_utilisateur/index.html.twig', [
            'role_utilisateurs' => $roleUtilisateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="role_utilisateur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $roleUtilisateur = new RoleUtilisateur();
        $form = $this->createForm(RoleUtilisateurType::class, $roleUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($roleUtilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('role_utilisateur_index');
        }

        return $this->render('role_utilisateur/new.html.twig', [
            'role_utilisateur' => $roleUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="role_utilisateur_show", methods={"GET"})
     */
    public function show(RoleUtilisateur $roleUtilisateur): Response
    {
        return $this->render('role_utilisateur/show.html.twig', [
            'role_utilisateur' => $roleUtilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="role_utilisateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RoleUtilisateur $roleUtilisateur): Response
    {
        $form = $this->createForm(RoleUtilisateurType::class, $roleUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('role_utilisateur_index');
        }

        return $this->render('role_utilisateur/edit.html.twig', [
            'role_utilisateur' => $roleUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="role_utilisateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RoleUtilisateur $roleUtilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$roleUtilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($roleUtilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('role_utilisateur_index');
    }




}
