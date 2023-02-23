<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223121124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492195E0F0');
        $this->addSql('ALTER TABLE user CHANGE daten daten DATE DEFAULT NULL, CHANGE datecr datecr DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492195E0F0');
        $this->addSql('ALTER TABLE `user` CHANGE daten daten DATE NOT NULL, CHANGE datecr datecr DATE NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6492195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON DELETE CASCADE');
    }
}
