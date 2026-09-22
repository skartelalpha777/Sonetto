<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260922133209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE image_produit ADD is_main TINYINT NOT NULL DEFAULT 0');
        // Récupère les photos déjà renseignées comme "image principale" du produit
        // avant de supprimer cette colonne, pour ne pas perdre de données.
        $this->addSql('INSERT INTO image_produit (produit_id, nom_fichier, position, is_main)
            SELECT id, image, 0, 1 FROM produit WHERE image IS NOT NULL AND image != \'\'');
        $this->addSql('ALTER TABLE produit DROP image');
        $this->addSql('ALTER TABLE image_produit ALTER is_main DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE produit ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE produit p
            INNER JOIN image_produit ip ON ip.produit_id = p.id AND ip.is_main = 1
            SET p.image = ip.nom_fichier');
        $this->addSql('ALTER TABLE image_produit DROP is_main');
    }
}
