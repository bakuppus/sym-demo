<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190903091212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE golf_course_guides (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_course_id INT UNSIGNED DEFAULT NULL, number_of_holes INT DEFAULT 18 NOT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B79ACBB7D17F50A6 (uuid), UNIQUE INDEX UNIQ_B79ACBB7731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE golf_course_guides ADD CONSTRAINT FK_B79ACBB7731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE golf_course_guides');
    }
}
