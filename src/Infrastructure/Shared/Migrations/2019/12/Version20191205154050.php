<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191205154050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_memberships DROP FOREIGN KEY FK_DE6DAEE399E6F5DF');
        $this->addSql('DROP INDEX IDX_active_partner_membership ON player_memberships');
        $this->addSql('ALTER TABLE player_memberships ADD state VARCHAR(255) NOT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE type type VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE player_memberships ADD CONSTRAINT FK_DE6DAEE399E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_active_partner_membership ON player_memberships (player_id, golf_club_id, duration_type, is_active, deleted_at)');
        $this->addSql('ALTER TABLE memberships DROP total');
        $this->addSql('ALTER TABLE fees CHANGE dtype discriminator VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX FK_WRHUZNAM51SPDF30 ON fees (fee_unit_id, membership_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX FK_WRHUZNAM51SPDF30 ON fees');
        $this->addSql('ALTER TABLE fees CHANGE discriminator dtype VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE memberships ADD total INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE player_memberships DROP FOREIGN KEY FK_DE6DAEE399E6F5DF');
        $this->addSql('DROP INDEX IDX_active_partner_membership ON player_memberships');
        $this->addSql('ALTER TABLE player_memberships DROP state, CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE type type VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE player_memberships ADD CONSTRAINT FK_DE6DAEE399E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('CREATE INDEX IDX_active_partner_membership ON player_memberships (player_id, golf_club_id, type, is_active, deleted_at)');
    }
}
