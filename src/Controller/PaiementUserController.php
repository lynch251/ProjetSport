<?php

namespace App\Controller;
use App\Entity\Abonnement;
use App\Entity\Paiement;
use App\Entity\Utilisateur;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paiementUser")
 */
class PaiementUserController extends AbstractController
{
    /**
     * @Route("/stopRenouveler/{id}/", name="stopRenouveler", methods={"GET"})
     */
    public function stopRenouveler(Abonnement $abonnement): Response
    {   //Symfony va automatiquement rechercher l'abonnementUser ayant comme id le {idAbonnement} de l'URL
        //$repository = $this->getDoctrine()->getRepository(Abonnement::class);
       // $abo = $repository->findOneBy(
       //     ['id' => $idAbonnement]
       // );
        dump($abonnement);
        $abonnement->setRenouveler(false);
        $abonnement->setDateDebut(DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s")));
        $em = $this->getDoctrine()->getManager();
        $em->merge($abonnement);
        $em->flush();

        return $this->render('abonnementUser/index.html.twig', [
            'abonnements' => $this->getUser()->getAbonnements()
        ]);
    }


    /**
     * @Route("/", name="paiement_index", methods={"GET"})
     */
    public function index( ): Response
    {


        return $this->render('paiementUser/parAbonnement.html.twig', [
            'abonnements' =>  $this->getUser()->getAbonnements(),
        ]);
    }

    /**
     * @Route("/paiement_index2", name="paiement_index2", methods={"GET"})
     */
    public function index2( ): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT a, p FROM App\Entity\Abonnement  a JOIN a.paiementUser p JOIN a.client c where c.id = '.$this->getUser()->getId() );
        $paiements = $query->getResult();

        return $this->render('paiementUser/index.html.twig', [
            'paiements' =>  $paiements,
        ]);
    }

}
