<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190306220700 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings CHANGE total_price total_price INT DEFAULT 0 NOT NULL, CHANGE base_price base_price INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants CHANGE price price INT DEFAULT 0 NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants CHANGE price price INT NOT NULL');
        $this->addSql('ALTER TABLE tee_time_bookings CHANGE total_price total_price INT NOT NULL, CHANGE base_price base_price INT NOT NULL');
    }
}
