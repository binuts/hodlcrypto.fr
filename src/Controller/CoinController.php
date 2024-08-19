<?php

namespace App\Controller;

use App\Entity\Cryptocurrencies;
use App\Form\CoinType;
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
        $coinGeckoCLient = new CoinGeckoClient();
        $list = $coinGeckoCLient->coins()->getList();

        $choices = [];
        foreach ($list as $coin) {
            $choices[$coin['name']] = $coin['id'];
        }

        $form = $this->createForm(CoinType::class, null, [
            'choices' => $choices,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nameOfNewCoin = $form->getData();
            dump($nameOfNewCoin);
            $newToken = $coinGeckoCLient->coins()->getCoin($nameOfNewCoin['name'], [
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

                $this->addFlash('success', 'Token ' . $newToken['name'] . ' ajouté !');

            return $this->redirectToRoute('app_portfolio');
                }
        }

        return $this->render('coin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}