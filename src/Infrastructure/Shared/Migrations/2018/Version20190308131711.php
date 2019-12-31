<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190308131711 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership_golf_courses DROP FOREIGN KEY FK_1EE7ECAC77E29489');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC77E29489 FOREIGN KEY (membership_golf_course_group_id) REFERENCES memberships_golf_course_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E577E29489');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E577E29489 FOREIGN KEY (membership_golf_course_group_id) REFERENCES memberships_golf_course_groups (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E577E29489');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E577E29489 FOREIGN KEY (membership_golf_course_group_id) REFERENCES memberships_golf_course_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE membership_golf_courses DROP FOREIGN KEY FK_1EE7ECAC77E29489');
        $this->addSql('ALTER TABLE membership_golf_courses ADD CONSTRAINT FK_1EE7ECAC77E29489 FOREIGN KEY (membership_golf_course_group_id) REFERENCES memberships_golf_course_groups (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
