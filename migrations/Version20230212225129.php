<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212225129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD telephone INT NOT NULL, ADD adress VARCHAR(255) NOT NULL, ADD specialite VARCHAR(255) DEFAULT NULL, ADD daten DATE NOT NULL, ADD datecr DATE NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD id_fiche VARCHAR(255) DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP telephone, DROP adress, DROP specialite, DROP daten, DROP datecr, DROP nom, DROP prenom, DROP id_fiche, DROP is_verified');
    }
}
