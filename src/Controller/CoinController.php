<?php

namespace App\Controller;

use App\Entity\Cryptocurrencies;
use App\Form\CoinType;
use App\Form\CoinSearchType;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coin', name: 'app_coin_')]
class CoinController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coinToAdd = new Cryptocurrencies();
        $coinForm = $this->createForm(CoinType::class, $coinToAdd);
        $coinForm->handleRequest($request);

        if ($coinForm->isSubmitted() && $coinForm->isValid()) {
            $coin = $coinForm->getData();

            $newToken = new Cryptocurrencies();
            $token = $coin->getName();
            $coins = new CoinGeckoClient();
            $newToken = $coins->coins()->getCoin($token, [
                'tickers' => 'true',
                'market_data' => 'true',
                'precision' => 4
            ]);

            $coinToAdd = $entityManager->getRepository(Cryptocurrencies::class)->findOneBy(['symbol' => $newToken['symbol']]);

            if ($coinToAdd !== null) {

                $this->addFlash('error', 'Token ' . $newToken['name'] . ' déjà existant !');

            return $this->redirectToRoute('app_coin_create');

            } else {
                $coin = new Cryptocurrencies();
                $coin->setName($newToken['name']);
                $coin->setSymbol($newToken['symbol']);
                $coin->setAllTimeHigh($newToken['market_data']['ath']['usd']);
                $coin->setDateAth(new \DateTime($newToken[
                    'market_data']['ath_date']['usd']));
                $coin->setAllTimeLow($newToken['market_data']['atl']['usd']);
                $coin->setDateAtl(new \DateTime($newToken[
                    'market_data']['atl_date']['usd']));
                $coin->setLastPrice($newToken['market_data']['current_price']['usd']);
                $coin->setDateLastPrice(new \DateTime('now'));

                $entityManager->persist($coin);
                $entityManager->flush();

                $this->addFlash('success', 'Token ' . $newToken['name'] . ' créé !');

            return $this->redirectToRoute('app_portfolio');
                }
        }

        return $this->render('coin/create.html.twig', [
            'coinForm' => $coinForm->createView(),
        ]);
    }
}