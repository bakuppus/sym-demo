<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20191025133141 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE play_right_imports (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, golf_ids JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', failed_golf_ids JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', status VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CE2AD35CD17F50A6 (uuid), INDEX IDX_CE2AD35C70E209E0 (golf_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE play_right_imports ADD CONSTRAINT FK_CE2AD35C70E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE play_right_imports');
    }
}
