<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190307114326 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E8707DE404');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E8731B2E4E');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E87E3C61F9');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E8707DE404 FOREIGN KEY (participant_owner_id) REFERENCES tee_time_booking_participants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E8731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES players (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66951FB354CD');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66953301C60');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD669599E6F5DF');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66951FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66953301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD669599E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD669599E6F5DF');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66951FB354CD');
        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66953301C60');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD669599E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66951FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66953301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E8707DE404');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E87E3C61F9');
        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E8731B2E4E');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E8707DE404 FOREIGN KEY (participant_owner_id) REFERENCES tee_time_booking_participants (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E8731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
