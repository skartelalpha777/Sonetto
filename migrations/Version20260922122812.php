<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260922122812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_produit (id INT AUTO_INCREMENT NOT NULL, nom_fichier VARCHAR(255) NOT NULL, position INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_BCB5BBFBF347EFB (produit_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE image_produit ADD CONSTRAINT FK_BCB5BBFBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit DROP images_galerie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_produit DROP FOREIGN KEY FK_BCB5BBFBF347EFB');
        $this->addSql('DROP TABLE image_produit');
        $this->addSql('ALTER TABLE produit ADD images_galerie LONGTEXT DEFAULT NULL');
    }
}
