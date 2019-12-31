<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190130164634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE period_rules (id INT UNSIGNED AUTO_INCREMENT NOT NULL, period_id INT UNSIGNED DEFAULT NULL, days INT NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, `interval` INT NOT NULL, status INT NOT NULL, slots INT NOT NULL, priority INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B8321159D17F50A6 (uuid), INDEX IDX_B8321159EC8B7ADE (period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periods (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_course_id INT UNSIGNED DEFAULT NULL, name VARCHAR(150) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_671798A2D17F50A6 (uuid), INDEX IDX_671798A2731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE period_rules ADD CONSTRAINT FK_B8321159EC8B7ADE FOREIGN KEY (period_id) REFERENCES periods (id)');
        $this->addSql('ALTER TABLE periods ADD CONSTRAINT FK_671798A2731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE period_rules DROP FOREIGN KEY FK_B8321159EC8B7ADE');
        $this->addSql('DROP TABLE period_rules');
        $this->addSql('DROP TABLE periods');
    }
}
