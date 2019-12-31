<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190625110802 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66952A6EF8E');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66952A6EF8E FOREIGN KEY (partner_type_id) REFERENCES golf_club_partner_types (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66952A6EF8E');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66952A6EF8E FOREIGN KEY (partner_type_id) REFERENCES golf_club_partner_types (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
