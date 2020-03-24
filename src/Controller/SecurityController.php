<?php
// src/Controller/SecurityController.php
namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Avis;
use App\Entity\RoleUtilisateur;
use App\Entity\Seance;
use App\Entity\Utilisateur;
use App\Form\AvisType;
use App\Form\InscriptionFormulaire;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Zend\Code\Scanner\Util;
use App\Security\FormLoginAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder ): Response
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


                        //Connexion manuelle de l'utilisateur nouvellement créé (merci google !)
                        $token = new UsernamePasswordToken($utilisateur, null, 'main', $utilisateur->getRoles());
                        $this->get('security.token_storage')->setToken($token);
                        // If the firewall name is not main, then the set value would be instead:
                        // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
                        $this->get('session')->set('_security_main', serialize($token));
                        // Fire the login event manually
                        $event = new InteractiveLoginEvent($request, $token);
                        $dispatcher = new EventDispatcher();
                        $dispatcher->dispatch("security.interactive_login", $event);

                        $repositorySeance = $this->getDoctrine()->getRepository(Seance::class);
                        $nbSeancesUser = $repositorySeance->findNumberByUser($this->getUser());

                        $repositoryAbonnement = $this->getDoctrine()->getRepository(Abonnement::class);
                        $nbAbonnementUser = $repositoryAbonnement->findNumberByUser($this->getUser());

                        return $this->render('accueil/accueilconnecte.html.twig', [
                                'utilisateur' => $this->getUser(),
                                'nbSeances' => $nbSeancesUser,
                                'nbAbonnementUser' => $nbAbonnementUser
                            ]
                        );
                    } else
                        $error_inscription = "Mail déjà inscrit.";

                } else
                    $error_inscription = "Login déjà pris.";
            }
            else
                $error_inscription = "Erreur confirmation mot de passe";
        }

        //Si on arrive là, c'est que l'utilisateur n'a pas essayé de se connecter ni de s'inscrire
        return $this->render('inscription/formulaireInscription.html.twig', [
                'last_username' => "",
                'error' => "",
                'error_inscription' => $error_inscription,
                'formUtilisateur' => $formUtilisateur->createView()
            ]
        );
    }



    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils ): Response
    {
        // retrouver une erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // retrouver le dernier identifiant de connexion utilisé
        $lastUsername = $authenticationUtils->getLastUsername();
        // génération du formulaire vide pour l'inscription éventuelle d'un nouvelle utilisateur

        $utilisateur = new Utilisateur();
        $formUtilisateur = $this->createForm( InscriptionFormulaire::class, $utilisateur);




        return $this->render('connexion/formulaireConnexion.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'error_inscription' => "",
                'formUtilisateur' => $formUtilisateur->createView()
            ]
        );
    }



    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

}

