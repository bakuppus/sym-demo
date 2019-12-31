<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191101152309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_items (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED NOT NULL, member_id INT UNSIGNED DEFAULT NULL, quantity INT NOT NULL, total INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, discriminator VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62809DB0D17F50A6 (uuid), INDEX IDX_62809DB08D9F6D38 (order_id), INDEX IDX_62809DB07597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT UNSIGNED AUTO_INCREMENT NOT NULL, club_id INT UNSIGNED NOT NULL, course_id INT UNSIGNED NOT NULL, customer_id INT UNSIGNED DEFAULT NULL, booking_id INT UNSIGNED DEFAULT NULL, booking_participant_id INT UNSIGNED DEFAULT NULL, membership_id INT UNSIGNED DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, state VARCHAR(255) NOT NULL, items_total INT NOT NULL, total INT NOT NULL, currency_code VARCHAR(3) NOT NULL, locale_code VARCHAR(255) NOT NULL, payment_state VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, discriminator VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E52FFDEED17F50A6 (uuid), INDEX IDX_E52FFDEE61190A32 (club_id), INDEX IDX_E52FFDEE591CC992 (course_id), INDEX IDX_E52FFDEE9395C3F3 (customer_id), INDEX IDX_E52FFDEE3301C60 (booking_id), INDEX IDX_E52FFDEE673CBE9 (booking_participant_id), INDEX IDX_E52FFDEE1FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB07597D3FE FOREIGN KEY (member_id) REFERENCES player_memberships (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE61190A32 FOREIGN KEY (club_id) REFERENCES golf_clubs (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE591CC992 FOREIGN KEY (course_id) REFERENCES golf_clubs (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE673CBE9 FOREIGN KEY (booking_participant_id) REFERENCES tee_time_booking_participants (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
    }
}
