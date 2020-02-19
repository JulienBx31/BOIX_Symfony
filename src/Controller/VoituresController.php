<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;

class VoituresController extends AbstractController
{
    /**
     * @Route("/", name="voitures")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Voiture::class);
        $voitures = $repo->findAll();

        return $this->render('voitures/index.html.twig', [
            'controller_name' => 'VoituresController',
            'voitures' => $voitures
        ]);
    }

    /**
     * @Route("/registration", name="registration")
     * @Route("/edit/{id}", name="voiture_edit")
     */
    public function registration(Request $request, ObjectManager $manager)
    {
        $voiture = new Voiture();

        $form = $this->createForm(RegistrationType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // Si le formulaire à était soumit et si le form est valide
            $manager->persist($voiture); // Enregistre dans la base de donnée
            $manager->flush();
            $this->addFlash('success', 'La voiture à bien été enregistré !');
            return $this->redirectToRoute('voitures');
        }
        return $this->render('voitures/registration.html.twig', [
            'formVoiture' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="voiture_edit")
     */
    public function edit(Voiture $voiture, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(RegistrationType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { // Si le formulaire à était soumit et si le form est valide
            $manager->persist($voiture); // Enregistre dans la base de donnée
            $manager->flush();
            $this->addFlash('change', 'La voiture à bien été modifié !');
            return $this->redirectToRoute('voitures');
        }
        return $this->render('voitures/edit.html.twig', [
            'formVoiture' => $form->createView()
        ]);
    }

    /**
     * @Route("/voiture/{id}", name="voiture_show")
     */
    public function show(Voiture $voiture) {
        return $this->render('voitures/show.html.twig', [
            'voiture' => $voiture
        ]);
    }

    /**
     * @Route("/voiture/delete/{id}", name="voiture_delete")
     */
    public function delete(Voiture $voiture, ObjectManager $manager) {
        $manager->remove($voiture);
        $manager->flush();
        $this->addFlash('delete', 'La voiture à bien été supprimé !');
        return $this->redirectToRoute('voitures');
    }
}
