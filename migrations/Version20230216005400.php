<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216005400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordonnance ADD nompatient VARCHAR(25) NOT NULL, ADD nommedecin VARCHAR(25) NOT NULL, DROP nom_patient, DROP nom_medecin, CHANGE date_o dateo DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ordonnance ADD nom_patient VARCHAR(25) NOT NULL, ADD nom_medecin VARCHAR(25) NOT NULL, DROP nompatient, DROP nommedecin, CHANGE dateo date_o DATE NOT NULL');
    }
}
