<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190630175935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX start_time_idx ON tee_time_bookings (start_time)');
        $this->addSql('CREATE INDEX end_time_idx ON tee_time_bookings (end_time)');
        $this->addSql('CREATE INDEX deleted_at_idx ON tee_time_bookings (deleted_at)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX start_time_idx ON tee_time_bookings');
        $this->addSql('DROP INDEX end_time_idx ON tee_time_bookings');
        $this->addSql('DROP INDEX deleted_at_idx ON tee_time_bookings');
    }
}
