<?php

namespace App\Entity;

use DateTime;
use App\Entity\LigneCommande;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Commande
{
    public const STATUS_PENDING = "PENDING";
    public const STATUS_PAID = 'PAID';

    // Attribut de relation
    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="commande", orphanRemoval=true)
     * @var Collection<LigneCommande>
     */
    private $ligneCommandes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commandes")
     */
    private $customer;

    // Constructeur
    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

    // Gestion des événements
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (empty($this->createAt)) {
            $this->createAt = new DateTime();
        }
    }

    /**
     *@ORM\PreFlush
     */
    public function preFlush()
    {
        $total = 0;

        foreach ($this->ligneCommandes as $ligneCommande) {
            $total += $ligneCommande->getTotal();
        }

        $this->total = $total;
    }

    // Attribut metier
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = SELF::STATUS_PENDING;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $addressDelivery = [];

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): ?Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getCommande() === $this) {
                $ligneCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getAddressDelivery(): ?array
    {
        return $this->addressDelivery;
    }

    public function setAddressDelivery(?array $addressDelivery): self
    {
        $this->addressDelivery = $addressDelivery;

        return $this;
    }
}
