<?php

namespace App\Controller;

use App\Repository\CryptocurrenciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PortfolioController extends AbstractController
{
    private $cryptocurrenciesRepository;

    public function __construct(CryptocurrenciesRepository $cryptocurrenciesRepository)
    {
        $this->cryptocurrenciesRepository = $cryptocurrenciesRepository;
    }

    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(): Response
    {
        $cryptoCount = $this->cryptocurrenciesRepository->countAllCryptocurrencies();
        dump($cryptoCount);

        return $this->render('portfolio/index.html.twig', [
            'controller_name' => 'PortfolioController',
            'crypto_count' => $cryptoCount,
        ]);
    }
}
