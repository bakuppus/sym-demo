<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190218130540 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players ADD personal_membership_id INT UNSIGNED DEFAULT NULL, ADD active_membership_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6A15EF117 FOREIGN KEY (personal_membership_id) REFERENCES memberships (id)');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6C1E9906F FOREIGN KEY (active_membership_id) REFERENCES memberships (id)');
        $this->addSql('CREATE INDEX IDX_264E43A6A15EF117 ON players (personal_membership_id)');
        $this->addSql('CREATE INDEX IDX_264E43A6C1E9906F ON players (active_membership_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6A15EF117');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6C1E9906F');
        $this->addSql('DROP INDEX IDX_264E43A6A15EF117 ON players');
        $this->addSql('DROP INDEX IDX_264E43A6C1E9906F ON players');
        $this->addSql('ALTER TABLE players DROP personal_membership_id, DROP active_membership_id');
    }
}
