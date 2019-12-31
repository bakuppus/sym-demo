<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181217062434 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE golf_clubs (id INT UNSIGNED AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(150) NOT NULL, git_id VARCHAR(50) NOT NULL, lonlat POINT NOT NULL COMMENT \'(DC2Type:point)\', phone VARCHAR(20) NOT NULL, email VARCHAR(150) NOT NULL, website VARCHAR(150) NOT NULL, description VARCHAR(1000) NOT NULL, description_short VARCHAR(250) NOT NULL, booking_information VARCHAR(1000) NOT NULL, booking_information_short VARCHAR(250) NOT NULL, sync_with_git TINYINT(1) NOT NULL, admin_assure_bookable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A20E893DD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE golf_clubs');
    }
}
