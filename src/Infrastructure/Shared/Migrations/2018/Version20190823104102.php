<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190823104102 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_facts DROP INDEX UNIQ_247677DD99E6F5DF, ADD INDEX IDX_247677DD99E6F5DF (player_id)');
        $this->addSql('ALTER TABLE player_facts ADD golf_club_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE player_facts ADD CONSTRAINT FK_247677DD70E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
        $this->addSql('CREATE INDEX IDX_247677DD70E209E0 ON player_facts (golf_club_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_facts DROP INDEX IDX_247677DD99E6F5DF, ADD UNIQUE INDEX UNIQ_247677DD99E6F5DF (player_id)');
        $this->addSql('ALTER TABLE player_facts DROP FOREIGN KEY FK_247677DD70E209E0');
        $this->addSql('DROP INDEX IDX_247677DD70E209E0 ON player_facts');
        $this->addSql('ALTER TABLE player_facts DROP golf_club_id');
    }
}
