<?php

namespace App\Entity;

use App\Repository\TransactionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionsRepository::class)]
class Transactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10)]
    private ?string $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10)]
    private ?string $price_at_transaction_in_usdt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transaction_date = null;

    public const TRANSACTION_TYPE_BUY = 'buy';
    public const TRANSACTION_TYPE_SELL = 'sell';

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $transaction_type = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cryptocurrencies $crypto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionType(): ?string
    {
        return $this->transaction_type;
    }

    public function setTransactionType(string $transactionType): self
    {
        if (!in_array($transactionType, [self::TRANSACTION_TYPE_BUY, self::TRANSACTION_TYPE_SELL])) {
            throw new \InvalidArgumentException("Invalid transaction type");
        }

        $this->transaction_type = $transactionType;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPriceAtTransactionInUsdt(): ?string
    {
        return $this->price_at_transaction_in_usdt;
    }

    public function setPriceAtTransactionInUsdt(string $price_at_transaction_in_usdt): static
    {
        $this->price_at_transaction_in_usdt = $price_at_transaction_in_usdt;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transaction_date;
    }

    public function setTransactionDate(\DateTimeInterface $transaction_date): static
    {
        $this->transaction_date = $transaction_date;

        return $this;
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

}
