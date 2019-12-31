<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190314052113 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE memberships_golf_course_groups ADD membership_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE memberships_golf_course_groups ADD CONSTRAINT FK_63A057071FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('CREATE INDEX IDX_63A057071FB354CD ON memberships_golf_course_groups (membership_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE memberships_golf_course_groups DROP FOREIGN KEY FK_63A057071FB354CD');
        $this->addSql('DROP INDEX IDX_63A057071FB354CD ON memberships_golf_course_groups');
        $this->addSql('ALTER TABLE memberships_golf_course_groups DROP membership_id');
    }
}
