<?php

namespace App\Form;

use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CoinSearchType extends AbstractType
{
    private CoinGeckoClient $coinGeckoClient;

    public function __construct(CoinGeckoClient $coinGeckoClient)
    {
        $this->coinGeckoClient = $coinGeckoClient;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $coins = $this->coinGeckoClient->coins()->getList();

        $choices = [];
        foreach ($coins as $coin) {
            $choices[$coin['id']] = $coin['name'];
        }

        $builder
            ->add('coin', ChoiceType::class, [
                'choices' => $choices,
                'placeholder' => 'Choose a coin',
                'required' => true,
            ]);
    }
}
