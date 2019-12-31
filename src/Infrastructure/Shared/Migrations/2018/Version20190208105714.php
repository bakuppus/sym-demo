<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190208105714 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_clubs ADD is_sync_with_git TINYINT(1) NOT NULL, ADD is_admin_assure_bookable TINYINT(1) NOT NULL, DROP sync_with_git, DROP admin_assure_bookable');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_clubs ADD sync_with_git TINYINT(1) NOT NULL, ADD admin_assure_bookable TINYINT(1) NOT NULL, DROP is_sync_with_git, DROP is_admin_assure_bookable');
    }
}
