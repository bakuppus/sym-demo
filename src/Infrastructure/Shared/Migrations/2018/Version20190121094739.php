<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190121094739 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE membership_golf_courses (id INT UNSIGNED AUTO_INCREMENT NOT NULL, membership_id INT UNSIGNED DEFAULT NULL, golf_course_id INT UNSIGNED DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1EE7ECACD17F50A6 (uuid), INDEX IDX_1EE7ECAC1FB354CD (membership_id), INDEX IDX_1EE7ECAC731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE memberships (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, name VARCHAR(150) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_865A4776D17F50A6 (uuid), INDEX IDX_865A477670E209E0 (golf_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A477670E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership_golf_courses DROP FOREIGN KEY FK_1EE7ECAC1FB354CD');
        $this->addSql('DROP TABLE membership_golf_courses');
        $this->addSql('DROP TABLE memberships');
    }
}
