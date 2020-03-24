<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/machine")
 */
class MachineController extends AbstractController
{
    /**
     * @Route("/", name="machine_index", methods={"GET"})
     */
    public function index(MachineRepository $machineRepository): Response
    {
        return $this->render('machine/index.html.twig', [
            'machines' => $machineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="machine_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Ajout du logo de la machine si présent
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                try {
                    $logoFile->move(
                        $this->getParameter('images_machines'),
                        $newFilename
                    );
                } catch(FileException $e) {
                    echo $e->getMessage();
                }
                $machine->setLogo($newFilename);
            }

            // Persistance de l'objet machine
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($machine);
            $entityManager->flush();

            return $this->redirectToRoute('machine_index');
        }

        return $this->render('machine/new.html.twig', [
            'machine' => $machine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="machine_show", methods={"GET"})
     */
    public function show(Machine $machine): Response
    {
        return $this->render('machine/show.html.twig', [
            'machine' => $machine,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="machine_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Machine $machine): Response
    {
        if ($machine->getLogo()) {
            $machine->setLogoFile(
             new File($this->getParameter('images_machines') . $machine->getLogo()));
        }

        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('machine_index');
        }
        return $this->render('machine/edit.html.twig', [
            'machine' => $machine,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="machine_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Machine $machine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$machine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($machine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('machine_index');
    }
}
