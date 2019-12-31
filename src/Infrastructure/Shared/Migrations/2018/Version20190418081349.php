<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190418081349 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E82A6EF8E');
        $this->addSql('DROP INDEX IDX_4C19F5E82A6EF8E ON tee_time_bookings');
        $this->addSql('ALTER TABLE tee_time_bookings DROP partner_type_id');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD partner_type_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66952A6EF8E FOREIGN KEY (partner_type_id) REFERENCES golf_club_partner_types (id)');
        $this->addSql('CREATE INDEX IDX_DBD66952A6EF8E ON tee_time_booking_participants (partner_type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66952A6EF8E');
        $this->addSql('DROP INDEX IDX_DBD66952A6EF8E ON tee_time_booking_participants');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP partner_type_id');
        $this->addSql('ALTER TABLE tee_time_bookings ADD partner_type_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E82A6EF8E FOREIGN KEY (partner_type_id) REFERENCES golf_club_partner_types (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4C19F5E82A6EF8E ON tee_time_bookings (partner_type_id)');
    }
}
