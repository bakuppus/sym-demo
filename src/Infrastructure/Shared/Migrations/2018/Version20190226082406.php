<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190226082406 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE players_favorite_courses (player_id INT UNSIGNED NOT NULL, golf_course_id INT UNSIGNED NOT NULL, INDEX IDX_7BB3DDE299E6F5DF (player_id), INDEX IDX_7BB3DDE2731B2E4E (golf_course_id), PRIMARY KEY(player_id, golf_course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE players_favorite_courses ADD CONSTRAINT FK_7BB3DDE299E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE players_favorite_courses ADD CONSTRAINT FK_7BB3DDE2731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE players_favorite_courses');
    }
}
