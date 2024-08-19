<?php

namespace App\Controller;

use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil', name: 'app_')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'profil')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour');

            return $this->redirectToRoute('app_profil');
        }
        return $this->render('profil/index.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    #[Route('/edit', name: 'profil_edit')]
    public function edit(): Response
    {
        return $this->render('profil/edit.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/password', name: 'profil_password')]
    public function password(): Response
    {
        return $this->render('profil/password.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
