<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\RoleUtilisateur;
use App\Form\InscriptionFormulaire;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur_index", methods={"GET"})
     */
    public function index(): Response
    {
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder ): Response
    {
        //Request : la requête http est les différentes data associée
        //$encoder : un objet fournit automatiquement par symfony sachant encoder les mots de passe

        //Gestion du formulaire pour une nouvelle inscription
        $utilisateur = new Utilisateur(); //Création de l'utilisateur vierge pour alimenter le formulaire d'interface
        $formUtilisateur = $this->createForm( InscriptionFormulaire::class, $utilisateur);
        $formUtilisateur->handleRequest($request); //On demande au formulaire "InscriptionFormulaire" de gérer la requête HTTP. Il paramétrera si possible $utilisateur avec les datas de la requête HTTP.
        $error_inscription ="";


        if ($formUtilisateur->isSubmitted() && $formUtilisateur->isValid()) {
            $password = $formUtilisateur->get('password')->getData();
            if($password   ==  $formUtilisateur->get('passwordConfirm')->getData())
            {
                $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
                $login = $utilisateur->getLogin();
                $users = $repository->findBy(['login' => $login]           );
                if (count($users) == 0) {
                    $users = $repository->findBy(
                        ['email' => $utilisateur->getEmail()]
                    );
                    if (count($users) == 0) {

                        //On est bon!! on peut envoyer l'utilisateur en base de données. Par défaut, il a le rôle : ROLE_USER (utilisateur de base)

                        //paramétrages complémentaire de l'objet utilisateur
                        $passEncode = $encoder->encodePassword($utilisateur, $password); //Encodage du mot de passe
                        $utilisateur->setPassword($passEncode);

                        // Gestion des rôles utilisateurs
                        $repositoryRole = $this->getDoctrine()->getRepository(RoleUtilisateur::class);
                        $entityRole = $repositoryRole->findOneBy(['titreSymfony' => "ROLE_USER"]);
                        $utilisateur->setRole($entityRole);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($utilisateur);
                        $em->flush();

                        return $this->redirectToRoute('utilisateur_index');

                    } else
                        $error_inscription = "Mail déjà inscrit.";

                } else
                    $error_inscription = "Login déjà pris.";
            }
            else
                $error_inscription = "Erreur confirmation mot de passe";
        }

        //Si on arrive là, c'est que l'utilisateur n'a pas essayé de se connecter ni de s'inscrire
        return $this->render('utilisateur/new.html.twig', [
                'last_username' => "",
                'error' => "",
                'error_inscription' => $error_inscription,
                'formUtilisateur' => $formUtilisateur->createView()
            ]
        );

    }


    /**
     * @Route("/trierRole", name="utilisateur_index_TrierRole", methods={"GET"})
     */
    public function indexparRole(): Response
    {
        $roles = $this->getDoctrine()
            ->getRepository(RoleUtilisateur::class)
            ->findAll();

        return $this->render('role_utilisateur/index_TrierRole.html.twig', [
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="utilisateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Utilisateur $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }


}
