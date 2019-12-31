<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190822101148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE permission_super_admin (super_admin_id INT UNSIGNED NOT NULL, permission_id INT UNSIGNED NOT NULL, INDEX IDX_A9122C10BBF91D3B (super_admin_id), INDEX IDX_A9122C10FED90CCA (permission_id), PRIMARY KEY(super_admin_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permission_super_admin ADD CONSTRAINT FK_A9122C10BBF91D3B FOREIGN KEY (super_admin_id) REFERENCES super_admins (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_super_admin ADD CONSTRAINT FK_A9122C10FED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE permission_super_admin');
    }
}
