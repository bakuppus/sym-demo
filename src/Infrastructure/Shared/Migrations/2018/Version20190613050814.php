<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190613050814 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE git_bookings (id INT UNSIGNED AUTO_INCREMENT NOT NULL, slot_id VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tee_time_bookings ADD git_booking_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E89D7D6BE7 FOREIGN KEY (git_booking_id) REFERENCES git_bookings (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C19F5E89D7D6BE7 ON tee_time_bookings (git_booking_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E89D7D6BE7');
        $this->addSql('DROP TABLE git_bookings');
        $this->addSql('DROP INDEX UNIQ_4C19F5E89D7D6BE7 ON tee_time_bookings');
        $this->addSql('ALTER TABLE tee_time_bookings DROP git_booking_id');
    }
}
