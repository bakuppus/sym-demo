<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181225090408 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE golf_courses (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, git_id VARCHAR(50) NOT NULL, name VARCHAR(150) NOT NULL, description VARCHAR(255) NOT NULL, lonlat POINT NOT NULL COMMENT \'(DC2Type:point)\', booking_information VARCHAR(255) NOT NULL, custom_description VARCHAR(1000) NOT NULL, custom_booking_information VARCHAR(1000) NOT NULL, use_custom_information TINYINT(1) NOT NULL, custom_description_short VARCHAR(1000) NOT NULL, custom_booking_information_short VARCHAR(1000) NOT NULL, active TINYINT(1) NOT NULL, use_dynamic_pricing TINYINT(1) NOT NULL, guest_booking_span TINYINT(1) NOT NULL, member_booking_span TINYINT(1) NOT NULL, admin_teetime_status TINYINT(1) NOT NULL, pay_and_play TINYINT(1) NOT NULL, tee_time_creation_status INT UNSIGNED NOT NULL, booking_type INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B85E599ED17F50A6 (uuid), INDEX IDX_B85E599E70E209E0 (golf_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE golf_courses ADD CONSTRAINT FK_B85E599E70E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
        $this->addSql('ALTER TABLE golf_clubs CHANGE phone phone VARCHAR(21) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE golf_courses');
        $this->addSql('ALTER TABLE golf_clubs CHANGE phone phone VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
