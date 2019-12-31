<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190214105959 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE membership_player (membership_id INT UNSIGNED NOT NULL, player_id INT UNSIGNED NOT NULL, INDEX IDX_E6A4CBFC1FB354CD (membership_id), INDEX IDX_E6A4CBFC99E6F5DF (player_id), PRIMARY KEY(membership_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membership_player ADD CONSTRAINT FK_E6A4CBFC1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membership_player ADD CONSTRAINT FK_E6A4CBFC99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE membership_player');
    }
}
