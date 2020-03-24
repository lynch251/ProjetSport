<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AvisType;
use App\Entity\Avis;
use \Datetime;

class FormAvisController extends AbstractController
{

     /**
     * @Route("/form/avis/update/", name="edit_route")
     */
    public function edit(Request $request)
    {//Affichage du formulaire avec gestion des mises à jour
        
        $form = $this->createForm(AvisType::class);

        $form->handleRequest($request);

        $avis = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            // va effectuer la requête d'UPDATE en base de données
            $em = $this->getDoctrine()->getManager();
            $em->merge($avis);
            $em->flush();
            
        }
        $repository = $this->getDoctrine()->getRepository(Avis::class);
        $listeAvis = $repository->findAll();
     
        return $this->render('form_avis/update.html.twig', array(
            'form' => $form->createView() ,'listeAvis' => $listeAvis));
    }

    /**
     * @Route("/form/avis", name="form_avis")
     */
    public function index(Request $request)
    {
        $avis = new Avis();
        $avis->setAuteurAvis('Chuck');
        $avis->setTitreAvis('Bradoc faites attention où vous mettez les pieds !');
        $avis->setMessageAvis('Mes pieds, je les mets où je veux et c\'est souvent dans la gueule!');
   
        $ymd = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $avis->setDateAvis($ymd );

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        $repository = $this->getDoctrine()->getRepository(Avis::class);
        if ($form->isSubmitted() && $form->isValid()) {//Gestion de la création et préparation en vue de la mise à jour
            $em = $this->getDoctrine()->getManager();
            $em->persist($avis); //L'avis est créé, mais son numéro auto n'est pas mis à jour dans l'objet $avis
            $em->flush();
            $listeAvis = $repository->findAll();
            $avis = $listeAvis[count($listeAvis)-1]; //on récupère l'avis
            $form = $this->createForm(AvisType::class, $avis); //On recharge le formulaire avec l'avis mis à jour
            return $this->render('form_avis/update.html.twig', array(
                'form' => $form->createView() ,'listeAvis' => $listeAvis));
        }
        else
        {//Affichage du formulaire
            $listeAvis = $repository->findAll();
            return $this->render('form_avis/index.html.twig', array(
                'form' => $form->createView() ,'listeAvis' => $listeAvis ));
        }

        

    }


   
}
