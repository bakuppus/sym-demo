<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190610111216 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE golf_club_partners (golf_club_id INT UNSIGNED NOT NULL, partner_club_id INT UNSIGNED NOT NULL, INDEX IDX_B88564D870E209E0 (golf_club_id), INDEX IDX_B88564D8A6F3692F (partner_club_id), PRIMARY KEY(golf_club_id, partner_club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE golf_club_partners ADD CONSTRAINT FK_B88564D870E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
        $this->addSql('ALTER TABLE golf_club_partners ADD CONSTRAINT FK_B88564D8A6F3692F FOREIGN KEY (partner_club_id) REFERENCES golf_clubs (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE golf_club_partners');
    }
}
