<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190709093619 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_golf_friends (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED DEFAULT NULL, friend_id INT UNSIGNED DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B0282399D17F50A6 (uuid), INDEX IDX_B028239999E6F5DF (player_id), INDEX IDX_B02823996A5458E8 (friend_id), UNIQUE INDEX UNIQ_J1A82MVOF7VK3UNJ (player_id, friend_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_golf_friends ADD CONSTRAINT FK_B028239999E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_golf_friends ADD CONSTRAINT FK_B02823996A5458E8 FOREIGN KEY (friend_id) REFERENCES players (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE player_golf_friends');
    }
}
