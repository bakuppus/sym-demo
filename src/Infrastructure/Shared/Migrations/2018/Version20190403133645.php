<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190403133645 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tee_times CHANGE status status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE period_overrides CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE period_rules CHANGE status status VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE period_overrides CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE period_rules CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE tee_times CHANGE status status SMALLINT NOT NULL');
    }
}
