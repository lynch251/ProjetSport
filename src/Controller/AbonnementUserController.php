<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Offre;
use App\Entity\Utilisateur;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/abonnementUser")
 */
class AbonnementUserController extends AbstractController
{
    /**
     * @Route("/", name="abonnement_index", methods={"GET"})
     */
    public function index( ): Response
    {

        return $this->render('abonnementUser/index.html.twig', [
            'abonnements' => $this->getUser()->getAbonnements()
        ]);
    }

    /**
     * @Route("/voirAbo/{idOffre}", name="voirAbo", methods={"GET"})
     */
    public function voirAbo(int $idOffre ): Response
    {


        return $this->render('paiementUser/parAbonnement.html.twig', [
            'abonnements' =>  $this->getUser()->getAbonnements(),
        ]);
    }

}
