<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * Toutes les photos du produit (table dédiée image_produit), y compris la
     * photo principale (celle avec isMain = true). Triée pour que la photo
     * principale arrive toujours en premier, ce qui sert à la fois de photo
     * de carte ET de première image du carrousel de la fiche produit.
     *
     * @var Collection<int, ImageProduit>
     */
    #[ORM\OneToMany(targetEntity: ImageProduit::class, mappedBy: 'produit', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['isMain' => 'DESC', 'position' => 'ASC'])]
    private Collection $images;

    /**
     * Sous-titre affiché sous le nom du produit. Si vide, la carte affiche
     * automatiquement "Caméra {resolution}".
     */
    #[ORM\Column(length: 180, nullable: true)]
    private ?string $sousTitre = null;

    /**
     * Points forts marketing (une phrase par ligne, affichés avec une coche
     * verte) affichés à la fois sur la carte produit ET la fiche détaillée.
     * Si vide, la carte retombe sur les champs techniques classiques
     * (autonomie / stockage / alimentation).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $pointsForts = null;

    /**
     * Points forts propres à la fiche détaillée (une phrase par ligne). Si
     * rempli, remplace complètement la liste "pointsForts" sur la fiche
     * détail (la carte produit garde, elle, sa propre liste "pointsForts").
     * Permet d'avoir un contenu différent entre la carte et la fiche détail.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $pointsFortsDetail = null;

    /**
     * Avertissement affiché uniquement sur la fiche détaillée, dans un encart
     * distinct (orange) juste après la description. Laisser vide si aucun
     * avertissement n'est nécessaire pour ce produit.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avertissement = null;

    /**
     * Si vrai, un badge "Produit à venir" s'affiche sur la carte et la fiche
     * détaillée, à la place du badge "Le plus populaire" éventuel.
     */
    #[ORM\Column]
    private bool $aVenir = false;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $resolution = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $autonomie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $stockage = null;

    #[ORM\Column(length: 50)]
    private ?string $alimentation = null;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, ImageProduit>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageProduit $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(ImageProduit $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return string[] Noms de fichiers de toutes les photos, photo principale en premier.
     */
    public function getGalerieComplete(): array
    {
        return array_map(
            fn (ImageProduit $image) => $image->getNomFichier(),
            $this->images->toArray()
        );
    }

    /**
     * Photo à afficher sur la carte produit (celle marquée "principale",
     * ou la première de la galerie à défaut).
     */
    public function getImagePrincipale(): ?string
    {
        $premiere = $this->images->first();

        return $premiere ? $premiere->getNomFichier() : null;
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

    public function getPointsForts(): ?string
    {
        return $this->pointsForts;
    }

    public function setPointsForts(?string $pointsForts): static
    {
        $this->pointsForts = $pointsForts;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getPointsFortsListe(): array
    {
        if (!$this->pointsForts) {
            return [];
        }

        $points = [];
        foreach (preg_split('/\r\n|\r|\n/', trim($this->pointsForts)) as $ligne) {
            $ligne = trim($ligne);
            if ($ligne !== '') {
                $points[] = $ligne;
            }
        }

        return $points;
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

    /**
     * @return string[]
     */
    public function getPointsFortsDetailListe(): array
    {
        if (!$this->pointsFortsDetail) {
            return [];
        }

        $points = [];
        foreach (preg_split('/\r\n|\r|\n/', trim($this->pointsFortsDetail)) as $ligne) {
            $ligne = trim($ligne);
            if ($ligne !== '') {
                $points[] = $ligne;
            }
        }

        return $points;
    }

    /**
     * Liste de points forts à afficher sur la fiche détaillée. Si
     * pointsFortsDetail est rempli, il remplace complètement la liste de la
     * carte (contenu indépendant, propre à la fiche détail). Sinon, la fiche
     * détail reprend simplement la même liste que la carte.
     *
     * @return string[]
     */
    public function getPointsFortsCompletListe(): array
    {
        $detail = $this->getPointsFortsDetailListe();

        return $detail !== [] ? $detail : $this->getPointsFortsListe();
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

    public function isAVenir(): bool
    {
        return $this->aVenir;
    }

    public function setAVenir(bool $aVenir): static
    {
        $this->aVenir = $aVenir;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function setResolution(?string $resolution): static
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function getAutonomie(): ?string
    {
        return $this->autonomie;
    }

    public function setAutonomie(?string $autonomie): static
    {
        $this->autonomie = $autonomie;

        return $this;
    }

    public function getStockage(): ?string
    {
        return $this->stockage;
    }

    public function setStockage(?string $stockage): static
    {
        $this->stockage = $stockage;

        return $this;
    }

    public function getAlimentation(): ?string
    {
        return $this->alimentation;
    }

    public function setAlimentation(string $alimentation): static
    {
        $this->alimentation = $alimentation;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}
