<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324173043 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateur ADD logo VARCHAR(255) DEFAULT NULL, CHANGE role_id role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE machine CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seance CHANGE intitule intitule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seance RENAME INDEX fk_seance_utilisateur TO IDX_DF7DFD0EFB88E14F');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX fk_utilisation_seance TO IDX_B02A3C43E3797A94');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX fk_utilisation_machine TO IDX_B02A3C43F6B75B26');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Utilisateur DROP logo, CHANGE role_id role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE machine CHANGE logo logo VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_general_ci, CHANGE description description VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE seance CHANGE intitule intitule VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE seance RENAME INDEX idx_df7dfd0efb88e14f TO FK_SEANCE_UTILISATEUR');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX idx_b02a3c43e3797a94 TO FK_UTILISATION_SEANCE');
        $this->addSql('ALTER TABLE utilisation RENAME INDEX idx_b02a3c43f6b75b26 TO FK_UTILISATION_MACHINE');
    }
}
