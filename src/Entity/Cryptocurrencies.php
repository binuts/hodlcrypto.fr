<?php

namespace App\Entity;

use App\Repository\CryptocurrenciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptocurrenciesRepository::class)]
class Cryptocurrencies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $symbol = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10, nullable: true)]
    private ?string $all_time_high = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_ath = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10, nullable: true)]
    private ?string $all_time_low = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_atl = null;

    /**
     * @var Collection<int, Transactions>
     */
    #[ORM\OneToMany(targetEntity: Transactions::class, mappedBy: 'crypto')]
    private Collection $transactions;

    /**
     * @var Collection<int, Transactions>
     */
    #[ORM\OneToMany(targetEntity: Transactions::class, mappedBy: 'crypto_id', orphanRemoval: true)]
    private Collection $crypto_id;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 10)]
    private ?string $last_price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_last_price = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->crypto_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getAllTimeHigh(): ?string
    {
        return $this->all_time_high;
    }

    public function setAllTimeHigh(?string $all_time_high): static
    {
        $this->all_time_high = $all_time_high;

        return $this;
    }

    public function getDateAth(): ?\DateTimeInterface
    {
        return $this->date_ath;
    }

    public function setDateAth(?\DateTimeInterface $date_ath): static
    {
        $this->date_ath = $date_ath;

        return $this;
    }

    public function getAllTimeLow(): ?string
    {
        return $this->all_time_low;
    }

    public function setAllTimeLow(?string $all_time_low): static
    {
        $this->all_time_low = $all_time_low;

        return $this;
    }

    public function getDateAtl(): ?\DateTimeInterface
    {
        return $this->date_atl;
    }

    public function setDateAtl(?\DateTimeInterface $date_atl): static
    {
        $this->date_atl = $date_atl;

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setCrypto($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCrypto() === $this) {
                $transaction->setCrypto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getCryptoId(): Collection
    {
        return $this->crypto_id;
    }

    public function addCryptoId(Transactions $cryptoId): static
    {
        if (!$this->crypto_id->contains($cryptoId)) {
            $this->crypto_id->add($cryptoId);
            $cryptoId->setCrypto($this);
        }

        return $this;
    }

    public function removeCryptoId(Transactions $cryptoId): static
    {
        if ($this->crypto_id->removeElement($cryptoId)) {
            // set the owning side to null (unless already changed)
            if ($cryptoId->getCrypto() === $this) {
                $cryptoId->setCrypto(null);
            }
        }

        return $this;
    }

    public function getLastPrice(): ?string
    {
        return $this->last_price;
    }

    public function setLastPrice(string $last_price): static
    {
        $this->last_price = $last_price;

        return $this;
    }

    public function getDateLastPrice(): ?\DateTimeInterface
    {
        return $this->date_last_price;
    }

    public function setDateLastPrice(\DateTimeInterface $date_last_price): static
    {
        $this->date_last_price = $date_last_price;

        return $this;
    }
}
