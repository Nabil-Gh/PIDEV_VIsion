<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306140215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE date date DATE DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7305E0476');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7305E0476 FOREIGN KEY (id_rec_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation CHANGE date date DATE NOT NULL, CHANGE etat etat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7305E0476');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7305E0476 FOREIGN KEY (id_rec_id) REFERENCES reclamation (id) ON DELETE CASCADE');
    }
}
