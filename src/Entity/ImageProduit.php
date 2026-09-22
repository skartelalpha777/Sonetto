<?php

namespace App\Entity;

use App\Repository\ImageProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Une photo de la galerie d'un produit (en plus de sa photo principale).
 * Permet à un produit d'avoir plusieurs images pour le carrousel de sa fiche détaillée.
 */
#[ORM\Entity(repositoryClass: ImageProduitRepository::class)]
class ImageProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    /**
     * Nom du fichier dans public/images/produits/.
     */
    #[ORM\Column(length: 255)]
    private ?string $nomFichier = null;

    /**
     * Ordre d'affichage dans le carrousel (les plus petits d'abord).
     */
    #[ORM\Column]
    private int $position = 0;

    /**
     * Photo principale du produit : c'est elle qui est affichée sur la carte
     * produit (accueil/catalogue). Elle apparaît aussi en premier dans le
     * carrousel de la fiche détaillée.
     */
    #[ORM\Column]
    private bool $isMain = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function isMain(): bool
    {
        return $this->isMain;
    }

    public function setIsMain(bool $isMain): static
    {
        $this->isMain = $isMain;

        return $this;
    }
}
