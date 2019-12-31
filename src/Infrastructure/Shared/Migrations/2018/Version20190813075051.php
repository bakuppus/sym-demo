<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190813075051 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings ADD origin_device_type VARCHAR(255) DEFAULT NULL, ADD origin_device VARCHAR(255) DEFAULT NULL, ADD origin_browser VARCHAR(255) DEFAULT NULL, ADD origin_source VARCHAR(255) DEFAULT NULL, ADD origin_platform VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD origin_device_type VARCHAR(255) DEFAULT NULL, ADD origin_device VARCHAR(255) DEFAULT NULL, ADD origin_browser VARCHAR(255) DEFAULT NULL, ADD origin_source VARCHAR(255) DEFAULT NULL, ADD origin_platform VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP origin_device_type, DROP origin_device, DROP origin_browser, DROP origin_source, DROP origin_platform');
        $this->addSql('ALTER TABLE tee_time_bookings DROP origin_device_type, DROP origin_device, DROP origin_browser, DROP origin_source, DROP origin_platform');
    }
}
