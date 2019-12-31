<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190209231816 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE price_modules (id INT UNSIGNED AUTO_INCREMENT NOT NULL, price_period_id INT UNSIGNED NOT NULL, settings JSON NOT NULL COMMENT \'(DC2Type:json)\', `order` SMALLINT NOT NULL, active TINYINT(1) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_45918B8BD17F50A6 (uuid), INDEX IDX_45918B8B6F3A4922 (price_period_id), UNIQUE INDEX period_price_module (price_period_id, discr), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_periods (id INT UNSIGNED AUTO_INCREMENT NOT NULL, golf_course_id INT UNSIGNED NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C3150FED17F50A6 (uuid), INDEX IDX_C3150FE731B2E4E (golf_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE price_modules ADD CONSTRAINT FK_45918B8B6F3A4922 FOREIGN KEY (price_period_id) REFERENCES price_periods (id)');
        $this->addSql('ALTER TABLE price_periods ADD CONSTRAINT FK_C3150FE731B2E4E FOREIGN KEY (golf_course_id) REFERENCES golf_courses (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE price_modules DROP FOREIGN KEY FK_45918B8B6F3A4922');
        $this->addSql('DROP TABLE price_modules');
        $this->addSql('DROP TABLE price_periods');
    }
}
