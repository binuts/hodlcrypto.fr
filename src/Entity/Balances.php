<?php

namespace App\Entity;

use App\Repository\BalancesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BalancesRepository::class)]
class Balances
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'balances')]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'balances')]
    private ?Cryptocurrencies $crypto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10)]
    private ?string $balance = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10)]
    private ?string $average_price = "0";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCrypto(): ?Cryptocurrencies
    {
        return $this->crypto;
    }

    public function setCrypto(?Cryptocurrencies $crypto): static
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getAveragePrice(): ?string
    {
        return $this->average_price;
    }

    public function setAveragePrice(string $average_price): static
    {
        $this->average_price = $average_price;

        return $this;
    }

}
