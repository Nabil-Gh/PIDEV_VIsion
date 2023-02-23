<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218153304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, namespe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD specialite_id INT DEFAULT NULL, DROP specialite');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6492195E0F0 ON user (specialite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492195E0F0');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP INDEX IDX_8D93D6492195E0F0 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD specialite VARCHAR(255) DEFAULT NULL, DROP specialite_id');
    }
}
