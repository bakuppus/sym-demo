<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190830145528 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_golf_course_interests (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED DEFAULT NULL, golf_course_id INT UNSIGNED DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_7EBD1DAED17F50A6 (uuid), INDEX IDX_7EBD1DAE99E6F5DF (player_id), INDEX IDX_7EBD1DAE731B2E4E (golf_course_id), UNIQUE INDEX UNIQ_2H60UB6CVSN4AHOU (player_id, golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_golf_course_interests ADD CONSTRAINT FK_7EBD1DAE99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE player_golf_course_interests ADD CONSTRAINT FK_7EBD1DAE731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE player_golf_course_interests');
    }
}
