<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190201074128 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E53420E9E4');
        $this->addSql('DROP INDEX IDX_129900E53420E9E4 ON golf_course_group_values');
        $this->addSql('ALTER TABLE golf_course_group_values CHANGE memberships_golf_course_group_id membership_golf_course_group_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E577E29489 FOREIGN KEY (membership_golf_course_group_id) REFERENCES memberships_golf_course_groups (id)');
        $this->addSql('CREATE INDEX IDX_129900E577E29489 ON golf_course_group_values (membership_golf_course_group_id)');
        $this->addSql('ALTER TABLE membership_golf_courses DROP FOREIGN KEY FK_1EE7ECAC3420E9E4');
        $this->addSql('DROP INDEX IDX_1EE7ECAC3420E9E4 ON membership_golf_courses');
        $this->addSql('ALTER TABLE membership_golf_courses CHANGE memberships_golf_course_group_id membership_golf_course_group_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC77E29489 FOREIGN KEY (membership_golf_course_group_id) REFERENCES memberships_golf_course_groups (id)');
        $this->addSql('CREATE INDEX IDX_1EE7ECAC77E29489 ON membership_golf_courses (membership_golf_course_group_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E577E29489');
        $this->addSql('DROP INDEX IDX_129900E577E29489 ON golf_course_group_values');
        $this->addSql('ALTER TABLE golf_course_group_values CHANGE membership_golf_course_group_id memberships_golf_course_group_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E53420E9E4 FOREIGN KEY (memberships_golf_course_group_id) REFERENCES memberships_golf_course_groups (id)');
        $this->addSql('CREATE INDEX IDX_129900E53420E9E4 ON golf_course_group_values (memberships_golf_course_group_id)');
        $this->addSql('ALTER TABLE membership_golf_courses DROP FOREIGN KEY FK_1EE7ECAC77E29489');
        $this->addSql('DROP INDEX IDX_1EE7ECAC77E29489 ON membership_golf_courses');
        $this->addSql('ALTER TABLE membership_golf_courses CHANGE membership_golf_course_group_id memberships_golf_course_group_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC3420E9E4 FOREIGN KEY (memberships_golf_course_group_id) REFERENCES memberships_golf_course_groups (id)');
        $this->addSql('CREATE INDEX IDX_1EE7ECAC3420E9E4 ON membership_golf_courses (memberships_golf_course_group_id)');
    }
}
