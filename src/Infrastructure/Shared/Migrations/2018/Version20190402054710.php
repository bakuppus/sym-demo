<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190402054710 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_DE6DAEE399E6F5DF1FB354CD ON player_memberships');
        $this->addSql('ALTER TABLE player_memberships CHANGE type type VARCHAR(10) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_memberships CHANGE type type SMALLINT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DE6DAEE399E6F5DF1FB354CD ON player_memberships (player_id, membership_id)');
    }
}
