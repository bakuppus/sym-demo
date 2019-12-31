<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191120071626 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion_tee_time_booking_participant (tee_time_booking_participant_id INT UNSIGNED NOT NULL, promotion_id INT UNSIGNED NOT NULL, INDEX IDX_2EF6D15C26896F5B (tee_time_booking_participant_id), INDEX IDX_2EF6D15C139DF194 (promotion_id), PRIMARY KEY(tee_time_booking_participant_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_tee_time_booking_participant ADD CONSTRAINT FK_2EF6D15C26896F5B FOREIGN KEY (tee_time_booking_participant_id) REFERENCES tee_time_booking_participants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_tee_time_booking_participant ADD CONSTRAINT FK_2EF6D15C139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE promotion_tee_time_booking');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion_tee_time_booking (tee_time_booking_id INT UNSIGNED NOT NULL, promotion_id INT UNSIGNED NOT NULL, INDEX IDX_90F6AA84D51EEBBA (tee_time_booking_id), INDEX IDX_90F6AA84139DF194 (promotion_id), PRIMARY KEY(tee_time_booking_id, promotion_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE promotion_tee_time_booking ADD CONSTRAINT FK_90F6AA84139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_tee_time_booking ADD CONSTRAINT FK_90F6AA84D51EEBBA FOREIGN KEY (tee_time_booking_id) REFERENCES tee_time_bookings (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE promotion_tee_time_booking_participant');
    }
}
