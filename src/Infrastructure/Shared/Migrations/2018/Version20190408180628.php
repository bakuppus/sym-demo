<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190408180628 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66951FB354CD');
        $this->addSql('DROP INDEX IDX_DBD66951FB354CD ON tee_time_booking_participants');
        $this->addSql('ALTER TABLE tee_time_booking_participants CHANGE membership_id owner_membership_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66956406A2A1 FOREIGN KEY (owner_membership_id) REFERENCES memberships (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_DBD66956406A2A1 ON tee_time_booking_participants (owner_membership_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_participants DROP FOREIGN KEY FK_DBD66956406A2A1');
        $this->addSql('DROP INDEX IDX_DBD66956406A2A1 ON tee_time_booking_participants');
        $this->addSql('ALTER TABLE tee_time_booking_participants CHANGE owner_membership_id membership_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66951FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_DBD66951FB354CD ON tee_time_booking_participants (membership_id)');
    }
}
