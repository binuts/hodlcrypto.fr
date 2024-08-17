<?php

namespace App\Form;

use App\Entity\Cryptocurrencies;
use App\Entity\Transactions;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// make a form to send a transaction in the database
// with date, token, quantity, tansactiontype (buy or sell) and use api coingecko to get the price of the token

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('crypto', EntityType::class, [
                'class' => Cryptocurrencies::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('quantity')
            ->add('transactionType', ChoiceType::class, [
                'choices' => [
                    'Buy' => 'buy',
                    'Sell' => 'sell'
                ]
            ])
            ->add('transactionDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('priceAtTransactionInUsdt')
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transactions::class,
        ]);
    }
}
