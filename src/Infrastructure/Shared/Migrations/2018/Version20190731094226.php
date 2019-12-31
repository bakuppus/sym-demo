<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190731094226 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players ADD merged_to_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6CA28E948 FOREIGN KEY (merged_to_id) REFERENCES players (id)');
        $this->addSql('CREATE INDEX IDX_264E43A6CA28E948 ON players (merged_to_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6CA28E948');
        $this->addSql('DROP INDEX IDX_264E43A6CA28E948 ON players');
        $this->addSql('ALTER TABLE players DROP merged_to_id');
    }
}
