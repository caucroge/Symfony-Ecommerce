<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    // Attribut de relation
    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="product")
     */
    private $ligneCommandes;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    // Constructeur
    public function __construct()
    {
        $this->ligneCommandes = new ArrayCollection();
    }

    // Attributs métier
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le nom du produit ne doit pas être vide !")
     */
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom du produit doit avoir entre 3 et 255 caractères !"
    )]
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    #[Assert\NotBlank(
        message: "Le prix ne doit pas être vide !",
    )]
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\Url(
        message: "L'url n'est pas valide"
    )]
    private $mainPicture;

    /**
     * @ORM\Column(type="text")
     */
    #[Assert\NotBlank(
        message: "La descrition courte ne doit pas être vide !"
    )]
    #[Assert\Length(
        min: 20,
        minMessage: "La description courte doit contenir au moins 20 caractères !"
    )]
    private $shortDescription;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setProduct($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->removeElement($ligneCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getProduct() === $this) {
                $ligneCommande->setProduct(null);
            }
        }

        return $this;
    }
}
