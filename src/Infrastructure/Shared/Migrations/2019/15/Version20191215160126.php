<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20191215160126 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_clubs ADD billing_company_name VARCHAR(255) DEFAULT NULL, ADD billing_company_address LONGTEXT DEFAULT NULL, ADD billing_phone VARCHAR(255) DEFAULT NULL, ADD billing_email VARCHAR(255) DEFAULT NULL, ADD billing_organisation_number VARCHAR(255) DEFAULT NULL, ADD billing_vat_number VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_clubs DROP billing_company_name, DROP billing_company_address, DROP billing_phone, DROP billing_email, DROP billing_organisation_number, DROP billing_vat_number');
    }
}
