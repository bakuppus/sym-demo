<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190219152652 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E5744E0351');
        $this->addSql('CREATE TABLE membership_rules (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(150) NOT NULL, name VARCHAR(150) NOT NULL, value LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8557BD315E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE rules');
        $this->addSql('DROP INDEX IDX_129900E5744E0351 ON golf_course_group_values');
        $this->addSql('ALTER TABLE golf_course_group_values CHANGE rule_id membership_rule_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E5FDE39500 FOREIGN KEY (membership_rule_id) REFERENCES membership_rules (id)');
        $this->addSql('CREATE INDEX IDX_129900E5FDE39500 ON golf_course_group_values (membership_rule_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E5FDE39500');
        $this->addSql('CREATE TABLE rules (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, value LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_899A993C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE membership_rules');
        $this->addSql('DROP INDEX IDX_129900E5FDE39500 ON golf_course_group_values');
        $this->addSql('ALTER TABLE golf_course_group_values CHANGE membership_rule_id rule_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E5744E0351 FOREIGN KEY (rule_id) REFERENCES rules (id)');
        $this->addSql('CREATE INDEX IDX_129900E5744E0351 ON golf_course_group_values (rule_id)');
    }
}
