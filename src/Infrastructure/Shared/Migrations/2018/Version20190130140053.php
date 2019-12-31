<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190130140053 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course_group_values DROP FOREIGN KEY FK_68EBECCB28516AB');
        $this->addSql('CREATE TABLE golf_course_group_values (id INT UNSIGNED AUTO_INCREMENT NOT NULL, rule_id INT UNSIGNED DEFAULT NULL, memberships_golf_course_group_id INT UNSIGNED DEFAULT NULL, value LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_129900E5744E0351 (rule_id), INDEX IDX_129900E53420E9E4 (memberships_golf_course_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE memberships_golf_course_groups (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_63A05707D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E5744E0351 FOREIGN KEY (rule_id) REFERENCES rules (id)');
        $this->addSql('ALTER TABLE golf_course_group_values ADD CONSTRAINT FK_129900E53420E9E4 FOREIGN KEY (memberships_golf_course_group_id) REFERENCES memberships_golf_course_groups (id)');
        $this->addSql('DROP TABLE course_group_values');
        $this->addSql('DROP TABLE memberships_course_group');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_course_group_values DROP FOREIGN KEY FK_129900E53420E9E4');
        $this->addSql('CREATE TABLE course_group_values (id INT UNSIGNED AUTO_INCREMENT NOT NULL, rule_id INT UNSIGNED DEFAULT NULL, memberships_course_group_id INT UNSIGNED DEFAULT NULL, value LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_68EBECCB744E0351 (rule_id), INDEX IDX_68EBECCB28516AB (memberships_course_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE memberships_course_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, uuid CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_F1D02044D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_group_values ADD CONSTRAINT FK_68EBECCB28516AB FOREIGN KEY (memberships_course_group_id) REFERENCES memberships_course_group (id)');
        $this->addSql('ALTER TABLE course_group_values ADD CONSTRAINT FK_68EBECCB744E0351 FOREIGN KEY (rule_id) REFERENCES rules (id)');
        $this->addSql('DROP TABLE golf_course_group_values');
        $this->addSql('DROP TABLE memberships_golf_course_groups');
    }
}
