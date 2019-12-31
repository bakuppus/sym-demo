<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190131081814 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership_golf_courses ADD memberships_golf_course_group_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC3420E9E4 FOREIGN KEY (memberships_golf_course_group_id) REFERENCES memberships_golf_course_groups (id)');
        $this->addSql('CREATE INDEX IDX_1EE7ECAC3420E9E4 ON membership_golf_courses (memberships_golf_course_group_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership_golf_courses DROP FOREIGN KEY FK_1EE7ECAC3420E9E4');
        $this->addSql('DROP INDEX IDX_1EE7ECAC3420E9E4 ON membership_golf_courses');
        $this->addSql('ALTER TABLE membership_golf_courses DROP memberships_golf_course_group_id');
    }
}
