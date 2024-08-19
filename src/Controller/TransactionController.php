<?php

namespace App\Controller;

use App\Entity\Transactions;
use App\Form\TransactionType;
use App\Repository\TransactionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transaction', name: 'app_transaction_')]
class TransactionController extends AbstractController
{
    #[Route('', name: 'new')]
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

            $entityManager->persist($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction prise en compte');

            return $this->redirectToRoute('app_portfolio');
        }

        return $this->render('transaction/index.html.twig', [
            'transactionForm' => $transactionForm->createView(),
        ]);
    }

    #[Route('/history', name: 'history')]
    public function history(TransactionsRepository $transactionsRepository)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // $transactions = $transactionsRepository->findBy(['user' => $user]);
        // usort($transactions, function ($a, $b) {
        //     return $b->getTransactionDate() <=> $a->getTransactionDate();
        // });
        // faire une jointure pour afficher le nom de la crypto au lieu de son id
        // joinleft
        $transactions = $transactionsRepository->createQueryBuilder('t')
            ->select('t', 'c.name as cryptoName')
            ->leftJoin('t.crypto', 'c')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.transaction_date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('transaction/history.html.twig', [
            'transactions' => $transactions,
        ]);
    }
}
