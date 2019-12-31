<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190821143749 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admins ADD partner_type_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE admins ADD CONSTRAINT FK_A2E0150F2A6EF8E FOREIGN KEY (partner_type_id) REFERENCES golf_club_partner_types (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_A2E0150F2A6EF8E ON admins (partner_type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admins DROP FOREIGN KEY FK_A2E0150F2A6EF8E');
        $this->addSql('DROP INDEX IDX_A2E0150F2A6EF8E ON admins');
        $this->addSql('ALTER TABLE admins DROP partner_type_id');
    }
}
