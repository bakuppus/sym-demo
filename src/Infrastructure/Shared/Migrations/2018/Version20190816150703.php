<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190816150703 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_permission (admin_id INT UNSIGNED NOT NULL, permission_id INT UNSIGNED NOT NULL, INDEX IDX_2877342F642B8210 (admin_id), INDEX IDX_2877342FFED90CCA (permission_id), PRIMARY KEY(admin_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_permission ADD CONSTRAINT FK_2877342F642B8210 FOREIGN KEY (admin_id) REFERENCES admins (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_permission ADD CONSTRAINT FK_2877342FFED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_permission');
    }
}
