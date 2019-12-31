<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190325084445 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_limits ADD membership_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE tee_time_booking_limits ADD CONSTRAINT FK_708C41201FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('CREATE INDEX IDX_708C41201FB354CD ON tee_time_booking_limits (membership_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_time_booking_limits DROP FOREIGN KEY FK_708C41201FB354CD');
        $this->addSql('DROP INDEX IDX_708C41201FB354CD ON tee_time_booking_limits');
        $this->addSql('ALTER TABLE tee_time_booking_limits DROP membership_id');
    }
}
