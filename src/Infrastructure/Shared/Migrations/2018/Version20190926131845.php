<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190926131845 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_emails (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, golf_courses LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', `from` DATETIME NOT NULL, `to` DATETIME NOT NULL, title VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, sent_at DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F71BD3ACD17F50A6 (uuid), INDEX IDX_F71BD3AC70E209E0 (golf_club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_emails ADD CONSTRAINT FK_F71BD3AC70E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE booking_emails');
    }
}
