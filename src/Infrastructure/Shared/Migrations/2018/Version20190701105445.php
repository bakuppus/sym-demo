<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190701105445 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX deleted_at_idx ON tee_time_bookings');
        $this->addSql('DROP INDEX end_time_idx ON tee_time_bookings');
        $this->addSql('DROP INDEX start_time_idx ON tee_time_bookings');
        $this->addSql('DROP INDEX status_idx ON tee_time_bookings');
        $this->addSql('CREATE INDEX IDX_PWKTJLAPRH1LHIQ4 ON tee_time_bookings (start_time, status, golf_course_id, deleted_at)');
        $this->addSql('CREATE INDEX IDX_53F0SVL7BIFCWJHB ON tee_times (golf_course_id, `from`)');
        $this->addSql('CREATE INDEX IDX_63F0SVL7BIFCWJHA ON tee_times (golf_course_id, `to`)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_PWKTJLAPRH1LHIQ4 ON tee_time_bookings');
        $this->addSql('CREATE INDEX deleted_at_idx ON tee_time_bookings (deleted_at)');
        $this->addSql('CREATE INDEX end_time_idx ON tee_time_bookings (end_time)');
        $this->addSql('CREATE INDEX start_time_idx ON tee_time_bookings (start_time)');
        $this->addSql('CREATE INDEX status_idx ON tee_time_bookings (status)');
        $this->addSql('DROP INDEX IDX_53F0SVL7BIFCWJHB ON tee_times');
        $this->addSql('DROP INDEX IDX_63F0SVL7BIFCWJHA ON tee_times');
    }
}
