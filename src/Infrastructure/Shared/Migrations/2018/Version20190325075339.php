<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190325075339 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tee_time_booking_limits (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED NOT NULL, golf_course_id INT UNSIGNED NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_708C4120D17F50A6 (uuid), INDEX IDX_708C412099E6F5DF (player_id), INDEX IDX_708C4120731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tee_time_booking_limits ADD CONSTRAINT FK_708C412099E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE tee_time_booking_limits ADD CONSTRAINT FK_708C4120731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
        $this->addSql('DROP TABLE booking_limits');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking_limits (id INT UNSIGNED AUTO_INCREMENT NOT NULL, player_id INT UNSIGNED NOT NULL, golf_course_id INT UNSIGNED NOT NULL, uuid CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_ED590345731B2E4E (golf_course_id), INDEX IDX_ED59034599E6F5DF (player_id), UNIQUE INDEX UNIQ_ED590345D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking_limits ADD CONSTRAINT FK_ED590345731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE booking_limits ADD CONSTRAINT FK_ED59034599E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE tee_time_booking_limits');
    }
}
