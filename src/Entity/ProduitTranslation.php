<?php

namespace App\Entity;

use App\Repository\ProduitTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

#[ORM\Entity(repositoryClass: ProduitTranslationRepository::class)]
class ProduitTranslation implements TranslationInterface
{
    use TranslationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $sousTitre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $pointsForts = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $pointsFortsDetail = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avertissement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSousTitre(): ?string
    {
        return $this->sousTitre;
    }

    public function setSousTitre(?string $sousTitre): static
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPointsForts(): ?string
    {
        return $this->pointsForts;
    }

    public function setPointsForts(?string $pointsForts): static
    {
        $this->pointsForts = $pointsForts;

        return $this;
    }

    public function getPointsFortsDetail(): ?string
    {
        return $this->pointsFortsDetail;
    }

    public function setPointsFortsDetail(?string $pointsFortsDetail): static
    {
        $this->pointsFortsDetail = $pointsFortsDetail;

        return $this;
    }

    public function getAvertissement(): ?string
    {
        return $this->avertissement;
    }

    public function setAvertissement(?string $avertissement): static
    {
        $this->avertissement = $avertissement;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->translatable;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->translatable = $produit;

        return $this;
    }
}
