<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190307092021 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP INDEX UNIQ_4C19F5E87E3C61F9, ADD INDEX IDX_4C19F5E87E3C61F9 (owner_id)');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E87E3C61F9');
        $this->addSql('ALTER TABLE tee_time_bookings ADD participant_owner_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E8707DE404 FOREIGN KEY (participant_owner_id) REFERENCES tee_time_booking_participants (id)');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES players (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C19F5E8707DE404 ON tee_time_bookings (participant_owner_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP INDEX IDX_4C19F5E87E3C61F9, ADD UNIQUE INDEX UNIQ_4C19F5E87E3C61F9 (owner_id)');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E8707DE404');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E87E3C61F9');
        $this->addSql('DROP INDEX UNIQ_4C19F5E8707DE404 ON tee_time_bookings');
        $this->addSql('ALTER TABLE tee_time_bookings DROP participant_owner_id');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES tee_time_booking_participants (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
