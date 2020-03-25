<?php
// src/Controller/SecurityController.php
namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Avis;
use App\Entity\Offre;
use App\Entity\Utilisateur;
use App\Entity\Seance;
use App\Entity\Utilisation;
use App\Form\AccordAbonnement;
use App\Form\AvisType;
use App\Form\InscriptionFormulaire;
use App\Form\ModifierInformationFormulaire;
use DateTime;
use phpDocumentor\Reflection\Types\Array_;
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

class MonCompteController extends AbstractController{


    /**
     * @Route("/pageaccueil", name="pageaccueil")
     */
    public function pageaccueil(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'utilisateur en session
        $utilisateur = $this->getUser();
        // Récupère le nombre de séances utilisateur
        $repositorySeance = $this->getDoctrine()->getRepository(Seance::class);
        $nbSeancesUser = $repositorySeance->findNumberByUser($utilisateur);
        // récupère les séances de l'utilisateur
        $listeSeances = $repositorySeance->findBy(['utilisateur'=>$utilisateur->getId()], ['date'=>'DESC']);

        $datesUtilisations = [];
        $coefficients = [];
        foreach ($listeSeances as $seance) {
            foreach ($seance->getUtilisations() as $utilisation) {
                $datesUtilisations[] = $seance->getDate()->format('Y-m-d');
                $coefficients[] = (int)$utilisation->getDuree() * (int)$utilisation->getQuantite();
            }
        }


        // récupère le nombre d'abonnements utilisateurs
        $repositoryAbonnement = $this->getDoctrine()->getRepository(Abonnement::class);
        $nbAbonnementUser = $repositoryAbonnement->findNumberByUser($utilisateur);

        return $this->render('accueil/accueilconnecte.html.twig', [
         'nbSeances' => $nbSeancesUser,
         'nbAbonnementUser' => $nbAbonnementUser,
            'listeSeances' => $listeSeances,
            'coefficients' => $coefficients,
            'datesUtilisations' => $datesUtilisations
        ]);
    }


    /**
     * @Route("/form_modifierInformation", name="page_modification_monCompte")
     */
    public function accueil(Request $request): Response
    {
        $form = $this->createForm(ModifierInformationFormulaire::class);
        $form->handleRequest($request);
        $utilisateurSession = $this->getUser();

        $ok = true;
        $message = "";
        if ($form->isSubmitted()) {
            //Le formulaire a été soumis
            $utilisateurForm = $form->getData(); //On attribut à un utilisateur les data du formulaire
            $utilisateurSession = $this->getUser(); //On récupère l'utilisateur en session

            $message = "";
            if($utilisateurForm->getLogin() != $utilisateurSession->getLogin())
            {
                //le login a été changé, on vérifie qu'il est unique
                $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
                $users = $repository->findBy(
                    ['login' => $utilisateurForm->getLogin()]
                );
                if(count($users) != 0) {
                    $ok = false;
                    $message .= "Login déjà utilisé par un autre utilisateur.";
                }
            }

            if($utilisateurForm->getEmail() != $utilisateurSession->getEmail())
            {
                //le mail a été changé, on vérifie qu'il est unique
                $repository = $this->getDoctrine()->getRepository(Utilisateur::class);
                $users = $repository->findBy(
                    ['email' => $utilisateurForm->getEmail()]
                );
                if(count($users) != 0) {
                    $ok = false;
                    $message .= " Mail déjà utilisé par un autre utilisateur.";
                }
            }

            if($ok)
            {//On peut faire la mise à jour en base de données
                $em = $this->getDoctrine()->getManager();
                $utilisateurSession->setLogin($utilisateurForm->getLogin());
                $utilisateurSession->setEmail($utilisateurForm->getEmail());
                $utilisateurSession->setNomUtilisateur($utilisateurForm->getNomUtilisateur());
                $utilisateurSession->setPrenomUtilisateur($utilisateurForm->getPrenomUtilisateur());

                $em->merge($utilisateurSession);
                $em->flush();
                dump($utilisateurSession);
                $message = "Modification réussie";
            }
           // $form->setData($form->setData($this->getUser()));
        }
        else {
            //Nous somme au premier passage, il faut alimenter le formulaire avec les informations de l'utilisateur connecté
            $form->setData($this->getUser());
        }
        return $this->render('form_modifierInformation/modifierUtilisateur.html.twig', [
            'formUtilisateur' => $form->createView(),
            'ok'=>$ok,
            'message'=>$message,
            'utilisateurSession' =>  $utilisateurSession
        ]);


    }


    /**
     * @Route("/monAbonnement", name="monAbonnement")
     */
    public function monAbonnement( )
    {
        $formUtilisateur = $this->createForm(AccordAbonnement::class);
        $repository = $this->getDoctrine()->getRepository(Offre::class);
        $offre = $repository->findOneBy(["id"=>$idOffre]);

        return $this->render('accueil/offresConfirme.html.twig',['offre' => $offre, 'formUtilisateur' => $formUtilisateur->createView()]);
    }

    /**
     * @Route("/mesPaiements", name="mesPaiements")
     */
    public function mesPaiements( )
    {
        $formUtilisateur = $this->createForm(AccordAbonnement::class);
        $repository = $this->getDoctrine()->getRepository(Offre::class);
        $offre = $repository->findOneBy(["id"=>$idOffre]);

        return $this->render('accueil/offresConfirme.html.twig',['offre' => $offre, 'formUtilisateur' => $formUtilisateur->createView()]);
    }

    /**
     * @Route("/Offres/{idOffre}", name="choisirOffre")
     */
    public function choisirOffre(int $idOffre)
    {
        $formUtilisateur = $this->createForm(AccordAbonnement::class);
        $repository = $this->getDoctrine()->getRepository(Offre::class);
        $offre = $repository->findOneBy(["id"=>$idOffre]);

        return $this->render('accueil/offresConfirme.html.twig',['offre' => $offre, 'formUtilisateur' => $formUtilisateur->createView()]);
    }

    /**
     * @Route("/OffresInscrire/{idOffre}", name="inscrire")
     */
    public function OffresInscrire(int $idOffre)
    {
        $formUtilisateur = $this->createForm(AccordAbonnement::class);

        $listeAbonnement = $this->getUser()->getAbonnements();
        $estAbonne = false;
        foreach ($listeAbonnement as $abo) {
            if ($abo->getEtat() == 1) {
                $estAbonne = true;
            }
        }
        if($estAbonne == false)
        {
            $em = $this->getDoctrine()->getManager();
            $abonnement = new Abonnement();
            $abonnement->setClient($this->getUser());

            //Manière directe de récupérer une instance d'un objet par son id
            $rep_Offre = $this->getDoctrine()->getRepository(Offre::class);
            $offre  = $rep_Offre->findOneBy(["id"=>$idOffre]);

            $abonnement->setOffre($offre);

            $dateDebut = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
            $abonnement->setDateDebut($dateDebut);
            $abonnement->setDateValidite($dateDebut);
            $abonnement->setRenouveler(true);
            $dateFin = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
            $dateFin->add(new \DateInterval("P1Y")); //On ajoute un interval d'un an
            $abonnement->setDateFin($dateFin);
            $em->merge($abonnement);
            $em->flush();
            return $this->render('accueil/offresInfoPaiement.html.twig',['offre' => $offre ]);
        }
        else
        {
            $formUtilisateur = $this->createForm(AccordAbonnement::class);
            $repository = $this->getDoctrine()->getRepository(Offre::class);
            $offre = $repository->findOneBy(["id"=>$idOffre]);

            return $this->render('accueil/offresConfirme.html.twig',['offre' => $offre, 'formUtilisateur' => $formUtilisateur->createView(), 'message' => "Vous avez déjà une offre d'associée à votre compte."]);
        }
    }
}