<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190708104348 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants ADD git_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE git_bookings CHANGE code code VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE git_bookings CHANGE code code VARCHAR(255) DEFAULT \'\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP git_id');
    }
}
