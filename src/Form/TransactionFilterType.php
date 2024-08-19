<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateTo', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('cryptoType', ChoiceType::class, [
                'choices' => [
                    'Bitcoin' => 'bitcoin',
                    'Ethereum' => 'ethereum',
                    // Ajoutez d'autres types de crypto-monnaies ici
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}