<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190729114803 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tee_sheet_links (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_course_id INT UNSIGNED DEFAULT NULL, hash VARCHAR(150) NOT NULL, available_date DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A3056F93D17F50A6 (uuid), INDEX IDX_A3056F93731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tee_sheet_links ADD CONSTRAINT FK_A3056F93731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
        $this->addSql('ALTER TABLE tee_sheet_links RENAME INDEX uniq_a3056f93d17f50a6 TO UNIQ_D7EEF301D17F50A6');
        $this->addSql('ALTER TABLE tee_sheet_links RENAME INDEX idx_a3056f93731b2e4e TO IDX_D7EEF301731B2E4E');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tee_sheet_links');
    }
}
