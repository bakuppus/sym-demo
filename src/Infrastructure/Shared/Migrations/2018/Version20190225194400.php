<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190225194400 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E87E3C61F9');
        $this->addSql('CREATE TABLE tee_time_booking_participants (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED DEFAULT NULL, membership_id INT UNSIGNED DEFAULT NULL, booking_id INT UNSIGNED DEFAULT NULL, price INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_DBD6695D17F50A6 (uuid), INDEX IDX_DBD669599E6F5DF (player_id), INDEX IDX_DBD66951FB354CD (membership_id), INDEX IDX_DBD66953301C60 (booking_id), UNIQUE INDEX FK_93F6915D99E6F5DF (player_id, booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD669599E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66951FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('ALTER TABLE tee_time_booking_participants ADD CONSTRAINT FK_DBD66953301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id)');
        $this->addSql('DROP TABLE booking_participants');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES tee_time_booking_participants (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_bookings DROP FOREIGN KEY FK_4C19F5E87E3C61F9');
        $this->addSql('CREATE TABLE booking_participants (id INT UNSIGNED AUTO_INCREMENT NOT NULL, booking_id INT UNSIGNED DEFAULT NULL, membership_id INT UNSIGNED DEFAULT NULL, player_id INT UNSIGNED DEFAULT NULL, created_at DATETIME NOT NULL, price INT NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_93F6915D1FB354CD (membership_id), INDEX IDX_93F6915D3301C60 (booking_id), INDEX IDX_93F6915D99E6F5DF (player_id), UNIQUE INDEX UNIQ_93F6915DD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_participants ADD CONSTRAINT FK_93F6915D1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE booking_participants ADD CONSTRAINT FK_93F6915D3301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE booking_participants ADD CONSTRAINT FK_93F6915D99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE tee_time_booking_participants');
        $this->addSql('ALTER TABLE tee_time_bookings ADD CONSTRAINT FK_4C19F5E87E3C61F9 FOREIGN KEY (owner_id) REFERENCES booking_participants (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
