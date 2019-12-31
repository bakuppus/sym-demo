<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190404111300 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_limits ADD tee_time_start DATETIME NOT NULL, ADD tee_time_end DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL, CHANGE membership_id membership_id INT UNSIGNED DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_limits DROP tee_time_start, DROP tee_time_end, DROP deleted_at, CHANGE membership_id membership_id INT UNSIGNED NOT NULL');
    }
}
