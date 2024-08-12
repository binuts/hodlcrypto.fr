<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil', name: 'app_')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
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
