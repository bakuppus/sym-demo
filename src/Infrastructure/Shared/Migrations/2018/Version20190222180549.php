<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190222180549 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_membership_settings (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED DEFAULT NULL, membership_id INT UNSIGNED DEFAULT NULL, inscription_date DATETIME NOT NULL, active_to DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_19E4867FD17F50A6 (uuid), INDEX IDX_19E4867F99E6F5DF (player_id), INDEX IDX_19E4867F1FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_membership_settings ADD CONSTRAINT FK_19E4867F99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_membership_settings ADD CONSTRAINT FK_19E4867F1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('DROP TABLE membership_player');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A15EF117');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6C1E9906F');
        $this->addSql('ALTER TABLE players ADD is_not_registered_yet TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE email email VARCHAR(150) DEFAULT NULL, CHANGE phone phone VARCHAR(150) DEFAULT NULL, CHANGE is_fire_base_authorized is_fire_base_authorized TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A15EF117 FOREIGN KEY (personal_membership_id) REFERENCES player_membership_settings (id)');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6C1E9906F FOREIGN KEY (active_membership_id) REFERENCES player_membership_settings (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A15EF117');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6C1E9906F');
        $this->addSql('CREATE TABLE membership_player (membership_id INT UNSIGNED NOT NULL, player_id INT UNSIGNED NOT NULL, INDEX IDX_E6A4CBFC1FB354CD (membership_id), INDEX IDX_E6A4CBFC99E6F5DF (player_id), PRIMARY KEY(membership_id, player_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membership_player ADD CONSTRAINT FK_E6A4CBFC1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membership_player ADD CONSTRAINT FK_E6A4CBFC99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE player_membership_settings');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A15EF117');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6C1E9906F');
        $this->addSql('ALTER TABLE players DROP is_not_registered_yet, CHANGE email email VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone phone VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE is_fire_base_authorized is_fire_base_authorized TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A15EF117 FOREIGN KEY (personal_membership_id) REFERENCES memberships (id)');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6C1E9906F FOREIGN KEY (active_membership_id) REFERENCES memberships (id)');
    }
}
