<?php

namespace App\Controller;

use App\Entity\Cryptocurrencies;
use App\Entity\Transactions;
use App\Form\TransactionType;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TransactionController extends AbstractController
{
    #[Route('/transaction', name: 'app_transaction_new')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transaction = new Transactions();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // write to database for exemple with attributes in Entity\Transactions

            $transaction->setUser($this->getUser());
            $transaction->setQuantity('10');
            $transaction->setTransactionType(Transactions::TRANSACTION_TYPE_BUY);
            $transaction->setPriceAtTransactionInUsdt('1000');
            $transaction->setTransactionDate(new \DateTime('Europe/Paris'));
            dump($entityManager->getRepository(Cryptocurrencies::class)->find(1));
            $transaction->setCrypto($entityManager->getRepository(Cryptocurrencies::class)->find(1));

            // write crytpo_id in cryptocurrency table
            // $transaction->setCrypto($entityManager->getRepository(Cryptocurrencies::class)->find(1));


            dump($transaction);

            $entityManager->persist($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction created!');

            return $this->redirectToRoute('app_transaction');
        }


        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
            'form' => $form->createView(),
        ]);
    }
}
