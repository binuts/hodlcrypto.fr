<?php

namespace App\Controller;

use App\Form\CoinSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoinGeckoController extends AbstractController
{
    #[Route('/coin/gecko', name: 'app_coin_gecko')]
    public function index(): Response
    {
        return $this->render('coin_gecko/index.html.twig', [
            'controller_name' => 'CoinGeckoController',
        ]);
    }

    #[Route('/coingecko/coins', name: 'coingecko_coins')]
    public function coins(): Response
    {
        $url = 'https://api.coingecko.com/api/v3/coins/list';
        $response = file_get_contents($url);
        $coins = json_decode($response) ;

        dump($coins);

        return $this->render('coingecko/coins.html.twig', [
            'coins' => $coins,
        ]);
    }

    // #[Route('/coingecko/search', name: 'coingecko_search') ]
    // public function search(Request $request): Response
    // {
    //     $form = $this->createForm(CoinSearchType::class);
    //     $form->handleRequest($request);

    //     $selectedCoin = null;

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $selectedCoinId = $form->get('coin')->getData();

    //         if ($selectedCoinId) {
    //             // Logique pour traiter la sélection du coin
    //             // Par exemple, appeler une autre API pour obtenir des détails
    //             $selectedCoin = $selectedCoinId;  // Remplace cela par la logique appropriée
    //         }
    //     }

    //     return $this->render('coingecko/coins.html.twig', [
    //         'form' => $form->createView(),
    //         'selectedCoin' => $selectedCoin,
    //     ]);
    // }
}
