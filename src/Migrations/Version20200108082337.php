<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200108082337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateur ADD role_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE Utilisateur ADD CONSTRAINT FK_9B80EC64D60322AC FOREIGN KEY (role_id) REFERENCES role_utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_9B80EC64D60322AC ON Utilisateur (role_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateur DROP FOREIGN KEY FK_9B80EC64D60322AC');
        $this->addSql('DROP INDEX IDX_9B80EC64D60322AC ON Utilisateur');
        $this->addSql('ALTER TABLE Utilisateur DROP role_id, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
