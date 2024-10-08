<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = $options['choices'];

        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $choices,
                'choice_label' => function ($choice, $key, $value) {
                    return $key;
                },
                'choice_value' => function ($choice) {
                    return $choice;
                },
                'attr' => ['class' => 'select2'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [],
        ]);
    }
}
