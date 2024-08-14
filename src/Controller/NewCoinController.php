<?php

namespace App\Controller;

use App\Entity\Cryptocurrencies;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewCoinController extends AbstractController
{
    #[Route('/new/coin', name: 'app_new_coin')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $client = new CoinGeckoClient();

        $coin = "bitcoin";

        $result = $client->coins()->getCoin($coin, [
            'tickers' => 'true',
            'market_data' => 'true',
            'precision' => 4
        ]);

        dump($result);

        $coin = $entityManager->getRepository(Cryptocurrencies::class)->findOneBy(['symbol' => $result['symbol']]);

        if ($coin) {
            $coin->setName($result['name']);
            $coin->setSymbol($result['symbol']);
            $coin->setAllTimeHigh($result['market_data']['ath']['usd']);
            $coin->setDateAth(new \DateTime($result[
                'market_data']['ath_date']['usd']));
            $coin->setAllTimeLow($result['market_data']['atl']['usd']);
            $coin->setDateAtl(new \DateTime($result[
                'market_data']['atl_date']['usd']));
            dump($coin);
            $entityManager->persist($coin);
            $entityManager->flush();
        } else {
        $coin = new Cryptocurrencies();
        $coin->setName('Bitcoin');
        $coin->setSymbol($result['tickers'][0]['base']);    
        $coin->setAllTimeHigh('30000');
        $coin->setDateAth(new \DateTime('2022-01-01'));
        $coin->setAllTimeLow('1000');
        $coin->setDateAtl(new \DateTime('2020-01-01'));

        dump($coin);

        $entityManager->persist($coin);
        $entityManager->flush();

        }

        return $this->render('new_coin/index.html.twig', [
            'controller_name' => 'NewCoinController',
        ]);
    }
}