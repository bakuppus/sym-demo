<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20191006184308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tee_time_booking_reviews (id INT UNSIGNED AUTO_INCREMENT NOT NULL, booking_id INT UNSIGNED DEFAULT NULL, player_id INT UNSIGNED DEFAULT NULL, participant_id INT UNSIGNED DEFAULT NULL, rating_id INT UNSIGNED DEFAULT NULL, type VARCHAR(30) DEFAULT NULL, reason VARCHAR(255) DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E8BEEBAD17F50A6 (uuid), INDEX IDX_E8BEEBA3301C60 (booking_id), INDEX IDX_E8BEEBA99E6F5DF (player_id), INDEX IDX_E8BEEBA9D1C3019 (participant_id), INDEX IDX_E8BEEBAA32EFC6 (rating_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tee_time_booking_reviews ADD CONSTRAINT FK_E8BEEBA3301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id)');
        $this->addSql('ALTER TABLE tee_time_booking_reviews ADD CONSTRAINT FK_E8BEEBA99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE tee_time_booking_reviews ADD CONSTRAINT FK_E8BEEBA9D1C3019 FOREIGN KEY (participant_id) REFERENCES tee_time_booking_participants (id)');
        $this->addSql('ALTER TABLE tee_time_booking_reviews ADD CONSTRAINT FK_E8BEEBAA32EFC6 FOREIGN KEY (rating_id) REFERENCES tee_time_booking_ratings (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tee_time_booking_reviews');
    }
}
