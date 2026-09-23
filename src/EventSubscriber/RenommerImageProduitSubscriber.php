<?php

namespace App\EventSubscriber;

use App\Entity\ImageProduit;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Quand une photo de produit est ajoutée ou remplacée dans l'administration,
 * renomme le fichier uploadé en "nom-du-produit-xxxxxx.ext" (slug + code
 * court aléatoire) au lieu de garder le nom d'origine du fichier envoyé.
 */
class RenommerImageProduitSubscriber
{
    private readonly string $uploadDir;

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%')] string $projectDir,
    ) {
        $this->uploadDir = rtrim($projectDir, '/\\').'/public/images/produits/';
    }

    #[AsEventListener]
    public function onBeforeEntityPersisted(BeforeEntityPersistedEvent $event): void
    {
        $this->renommerSiNecessaire($event->getEntityInstance());
    }

    #[AsEventListener]
    public function onBeforeEntityUpdated(BeforeEntityUpdatedEvent $event): void
    {
        $this->renommerSiNecessaire($event->getEntityInstance());
    }

    private function renommerSiNecessaire(object $entity): void
    {
        if (!$entity instanceof ImageProduit) {
            return;
        }

        $nomFichier = $entity->getNomFichier();
        $produit = $entity->getProduit();

        if (!$nomFichier || !$produit || !$produit->getNom()) {
            return;
        }

        // Un fichier déjà nommé selon notre convention (créé par ce même
        // listener lors d'un enregistrement précédent) n'a pas besoin d'être
        // retraité : ça veut dire qu'aucune nouvelle photo n'a été envoyée.
        $uow = $this->entityManager->getUnitOfWork();
        $ancienNom = $uow->isInIdentityMap($entity)
            ? ($uow->getOriginalEntityData($entity)['nomFichier'] ?? null)
            : null;
        if ($ancienNom === $nomFichier) {
            return;
        }

        $cheminActuel = $this->uploadDir.$nomFichier;
        if (!is_file($cheminActuel)) {
            return;
        }

        $extension = pathinfo($nomFichier, PATHINFO_EXTENSION);
        $slug = strtolower((string) $this->slugger->slug($produit->getNom()));
        $codeUnique = bin2hex(random_bytes(3));
        $nouveauNom = sprintf('%s-%s%s', $slug, $codeUnique, $extension ? '.'.$extension : '');

        if (@rename($cheminActuel, $this->uploadDir.$nouveauNom)) {
            $entity->setNomFichier($nouveauNom);

            // Nettoie l'ancien fichier physique si on vient de remplacer une photo existante.
            if ($ancienNom && $ancienNom !== $nomFichier && is_file($this->uploadDir.$ancienNom)) {
                @unlink($this->uploadDir.$ancienNom);
            }
        }
    }
}
