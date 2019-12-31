<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190410112047 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE golf_club_partner_types (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EF7DE56AD17F50A6 (uuid), INDEX IDX_EF7DE56A70E209E0 (golf_club_id), UNIQUE INDEX FK_2DXY8ERI7R03XR4G (golf_club_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE golf_club_partner_types ADD CONSTRAINT FK_EF7DE56A70E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE golf_club_partner_types');
    }
}
