<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190212081328 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_times ADD golf_course_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE tee_times ADD CONSTRAINT FK_2CEEE859731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_clubs (id)');
        $this->addSql('CREATE INDEX IDX_2CEEE859731B2E4E ON tee_times (golf_course_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_times DROP FOREIGN KEY FK_2CEEE859731B2E4E');
        $this->addSql('DROP INDEX IDX_2CEEE859731B2E4E ON tee_times');
        $this->addSql('ALTER TABLE tee_times DROP golf_course_id');
    }
}
