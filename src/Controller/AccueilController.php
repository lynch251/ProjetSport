<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Machine;
use App\Entity\Newsletter;
use App\Entity\Offre;
use App\Form\NewsletterType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request)
    {

        $repository = $this->getDoctrine()->getRepository(Offre::class);
        $listeOffre = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Machine::class);
        $listeMachines = $repository->findAll();

        // Récupère la liste des 5 derniers avis 5 étoiles
        $repository = $this->getDoctrine()->getRepository(Avis::class);
        $listeAvis = $repository->findBy(['nbEtoileAvis'=>'5'], ['dateAvis'=>'DESC'], 5 );

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'listeOffre' => $listeOffre,
            'listeMachines' => $listeMachines,
            'listeAvis' => $listeAvis
        ]);
    }




}
