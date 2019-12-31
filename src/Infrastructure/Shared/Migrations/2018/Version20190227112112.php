<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190227112112 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE golf_club_images (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D633D52F70E209E0 (golf_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE golf_course_images (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_course_id INT UNSIGNED DEFAULT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1AF2E032731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE golf_club_images ADD CONSTRAINT FK_D633D52F70E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
        $this->addSql('ALTER TABLE golf_course_images ADD CONSTRAINT FK_1AF2E032731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE golf_club_images');
        $this->addSql('DROP TABLE golf_course_images');
    }
}
