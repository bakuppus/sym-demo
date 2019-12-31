<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190401103204 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admins ADD phone VARCHAR(150) DEFAULT NULL, CHANGE first_name first_name VARCHAR(150) DEFAULT NULL, CHANGE last_name last_name VARCHAR(150) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2E0150F444F97DD ON admins (phone)');
        $this->addSql('ALTER TABLE super_admins ADD first_name VARCHAR(150) DEFAULT NULL, ADD last_name VARCHAR(150) DEFAULT NULL, ADD phone VARCHAR(150) DEFAULT NULL, ADD language VARCHAR(2) NOT NULL, DROP name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0391B6444F97DD ON super_admins (phone)');
        $this->addSql('ALTER TABLE players ADD language VARCHAR(2) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_A2E0150F444F97DD ON admins');
        $this->addSql('ALTER TABLE admins DROP phone, CHANGE first_name first_name VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE last_name last_name VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE players DROP language');
        $this->addSql('DROP INDEX UNIQ_6F0391B6444F97DD ON super_admins');
        $this->addSql('ALTER TABLE super_admins ADD name VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, DROP first_name, DROP last_name, DROP phone, DROP language');
    }
}
