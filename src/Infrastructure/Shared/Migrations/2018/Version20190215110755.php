<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190215110755 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE period_rules CHANGE tee_time_interval `interval` INT NOT NULL');
        $this->addSql('ALTER TABLE tee_times DROP FOREIGN KEY FK_2CEEE859731B2E4E');
        $this->addSql('ALTER TABLE tee_times ADD CONSTRAINT FK_2CEEE859731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE period_rules CHANGE `interval` tee_time_interval INT NOT NULL');
        $this->addSql('ALTER TABLE tee_times DROP FOREIGN KEY FK_2CEEE859731B2E4E');
        $this->addSql('ALTER TABLE tee_times ADD CONSTRAINT FK_2CEEE859731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_clubs (id)');
    }
}
