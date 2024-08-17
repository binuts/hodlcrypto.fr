<?php

namespace App\Controller;

use App\Entity\Cryptocurrencies;
use App\Entity\Transactions;
use App\Form\TransactionType;
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
        $transactionForm = $this->createForm(TransactionType::class, $transaction);
        $transactionForm->handleRequest($request);

        if ($transactionForm->isSubmitted() && $transactionForm->isValid()) {
            $transaction->setUser($this->getUser());
            $transaction->setCrypto($transactionForm->get('crypto')->getData());
            $transaction->setQuantity($transactionForm->get('quantity')->getData());
            $transaction->setTransactionType($transactionForm->get('transactionType')->getData());
            $transaction->setPriceAtTransactionInUsdt($transactionForm->get('priceAtTransactionInUsdt')->getData());
            $transaction->setTransactionDate($transactionForm->get('transactionDate')->getData());
            // $transaction->setCrypto($entityManager->getRepository(Cryptocurrencies::class)->find(2));


            dump($transaction);

            $entityManager->persist($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction prise en compte');

            return $this->redirectToRoute('app_portfolio');
        }


        return $this->render('transaction/index.html.twig', [
            'transactionForm' => $transactionForm->createView(),
        ]);
    }
}
