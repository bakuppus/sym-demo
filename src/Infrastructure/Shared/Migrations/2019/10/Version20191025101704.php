<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20191025101704 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_membership_to_assigns (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED DEFAULT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, membership_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F8127C4799E6F5DF (player_id), INDEX IDX_F8127C4770E209E0 (golf_club_id), UNIQUE INDEX unique_index (player_id, golf_club_id, membership_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_membership_to_assigns ADD CONSTRAINT FK_F8127C4799E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_membership_to_assigns ADD CONSTRAINT FK_F8127C4770E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE player_membership_to_assigns');
    }
}
