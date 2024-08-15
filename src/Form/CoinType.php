<?php

namespace App\Form;

use App\Entity\Cryptocurrencies;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $coinGeckoClient = new CoinGeckoClient();
        $list = $coinGeckoClient->coins()->getList();

        $choices = [];
        foreach ($list as $coin) {
            $choices[$coin['name']] = $coin['id'];
        }

        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $choices,
                'choice_label' => function ($choice, $key, $value) {
                    return $key;
                },
                'choice_value' => function ($choice) {
                    return $choice;
                },
            ])
            // ->add('symbol')
            // ->add('all_time_high')
            // ->add('date_ath', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('all_time_low')
            // ->add('date_atl', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('last_price')
            // ->add('date_last_price', null, [
            //     'widget' => 'single_text',
            // ])
        ;
        // dd($list);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cryptocurrencies::class,
        ]);
    }
}
