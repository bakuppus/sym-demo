<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190516072331 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transactions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, booking_id INT UNSIGNED DEFAULT NULL, player_id INT UNSIGNED DEFAULT NULL, payment_method_id INT UNSIGNED DEFAULT NULL, braintree_id VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, error_message VARCHAR(255) DEFAULT NULL, data LONGTEXT NOT NULL, is_paid_full_price TINYINT(1) DEFAULT \'0\' NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EAA81A4CD17F50A6 (uuid), INDEX IDX_EAA81A4C3301C60 (booking_id), INDEX IDX_EAA81A4C99E6F5DF (player_id), INDEX IDX_EAA81A4C5AA1164F (payment_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C3301C60 FOREIGN KEY (booking_id) REFERENCES tee_time_bookings (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C5AA1164F FOREIGN KEY (payment_method_id) REFERENCES player_payment_methods (id)');
        $this->addSql('ALTER TABLE player_payment_methods ADD deleted_at DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE transactions');
        $this->addSql('ALTER TABLE player_payment_methods DROP deleted_at');
    }
}
