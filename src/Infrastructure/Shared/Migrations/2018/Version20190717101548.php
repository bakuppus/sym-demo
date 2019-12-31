<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190717101548 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX IDX_73F0SVL7BIFCWJHC ON tee_times (golf_course_id, version)');
        $this->addSql('CREATE INDEX IDX_83F0SVL7BIFCWJHD ON tee_times (version)');
        $this->addSql('ALTER TABLE tee_times RENAME INDEX idx_53f0svl7bifcwjhb TO IDX_53F0SVL7BIFCWJHA');
        $this->addSql('ALTER TABLE tee_times RENAME INDEX idx_63f0svl7bifcwjha TO IDX_63F0SVL7BIFCWJHB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_73F0SVL7BIFCWJHC ON tee_times');
        $this->addSql('DROP INDEX IDX_83F0SVL7BIFCWJHD ON tee_times');
        $this->addSql('ALTER TABLE tee_times RENAME INDEX idx_53f0svl7bifcwjha TO IDX_53F0SVL7BIFCWJHB');
        $this->addSql('ALTER TABLE tee_times RENAME INDEX idx_63f0svl7bifcwjhb TO IDX_63F0SVL7BIFCWJHA');
    }
}
