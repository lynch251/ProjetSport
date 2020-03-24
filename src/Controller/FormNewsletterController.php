<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NewsletterType;
use App\Entity\Newsletter;
use \Datetime;

class FormNewsletterController extends AbstractController
{
     /**
     * @Route("/newsletter", name="newsletter")
     */
    public function index(Request $request)
    {
        $newsletter = new Newsletter();
     

        $ymd = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
        $newsletter->setDate($ymd);

        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        $repository = $this->getDoctrine()->getRepository(newsletter::class);
        if ($form->isSubmitted() && $form->isValid()) {//Gestion de la création et préparation en vue de la mise à jour
            
            if($request->get("action") == "Créer")
            {
                $newsletterCherche = $repository->findOneBy(array('mail' => $newsletter->getMail()));
                if($newsletterCherche == null)
                    {
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($newsletter); //L'newsletter est créé, mais son numéro auto n'est pas mis à jour dans l'objet $newsletter
                        $em->flush();
                        $listenewsletter = $repository->findAll();
                        $newsletter = $listenewsletter[count($listenewsletter)-1]; //on récupère l'newsletter
                        $message = "Inscription réussie";
                    }
                    else
                    {
                        $message = "Personne déjà inscrite";
                    }
            }
            else
            {
                if($request->get("action") == "Désinscription")
                {
                    $newsletterCherche = $repository->findOneBy(array('mail' => $newsletter->getMail()));
                    if($newsletterCherche != null)
                    {
                        $em = $this->getDoctrine()->getManager();
                        $em->remove($newsletterCherche); 
                        $em->flush();
                        $message = "Désinscription réussie";
                    }
                    else
                        $message = "Désinscription échouée";
                    $listenewsletter = $repository->findAll();
                }
                else
                    $message = "Erreur !!!";
            }


            
            $form = $this->createForm(newsletterType::class, $newsletter); //On recharge le formulaire avec l'newsletter mis à jour
            return $this->render('form_newsletter/newsletter.html.twig', array(
                'form' => $form->createView() , "message" => $message));
        }
        else
        {//Affichage du formulaire
            $listenewsletter = $repository->findAll();
            return $this->render('form_newsletter/newsletter.html.twig', array(
                'form' => $form->createView() ));
        }

        

    } 
    

}

    
