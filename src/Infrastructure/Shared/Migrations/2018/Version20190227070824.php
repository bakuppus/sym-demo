<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190227070824 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A15EF117');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6C1E9906F');
        $this->addSql('CREATE TABLE player_memberships (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED DEFAULT NULL, membership_id INT UNSIGNED DEFAULT NULL, golf_club_id INT UNSIGNED DEFAULT NULL, inscription_date DATETIME NOT NULL, active_to DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, type SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_DE6DAEE3D17F50A6 (uuid), INDEX IDX_DE6DAEE399E6F5DF (player_id), INDEX IDX_DE6DAEE31FB354CD (membership_id), INDEX IDX_DE6DAEE370E209E0 (golf_club_id), UNIQUE INDEX UNIQ_DE6DAEE399E6F5DF1FB354CD (player_id, membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_memberships ADD CONSTRAINT FK_DE6DAEE399E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_memberships ADD CONSTRAINT FK_DE6DAEE31FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('ALTER TABLE player_memberships ADD CONSTRAINT FK_DE6DAEE370E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
        $this->addSql('DROP TABLE player_membership_settings');
        $this->addSql('DROP INDEX IDX_264E43A6C1E9906F ON players');
        $this->addSql('ALTER TABLE players DROP active_membership_id');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A15EF117 FOREIGN KEY (personal_membership_id) REFERENCES player_memberships (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A15EF117');
        $this->addSql('CREATE TABLE player_membership_settings (id INT UNSIGNED AUTO_INCREMENT NOT NULL, membership_id INT UNSIGNED DEFAULT NULL, player_id INT UNSIGNED DEFAULT NULL, active_to DATETIME NOT NULL, created_at DATETIME NOT NULL, inscription_date DATETIME NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_19E4867F1FB354CD (membership_id), INDEX IDX_19E4867F99E6F5DF (player_id), UNIQUE INDEX UNIQ_19E4867F99E6F5DF1FB354CD (player_id, membership_id), UNIQUE INDEX UNIQ_19E4867FD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_membership_settings ADD CONSTRAINT FK_19E4867F1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE player_membership_settings ADD CONSTRAINT FK_19E4867F99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE player_memberships');
        $this->addSql('ALTER TABLE players ADD active_membership_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6C1E9906F FOREIGN KEY (active_membership_id) REFERENCES player_membership_settings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A15EF117 FOREIGN KEY (personal_membership_id) REFERENCES player_membership_settings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_264E43A6C1E9906F ON players (active_membership_id)');
    }
}
