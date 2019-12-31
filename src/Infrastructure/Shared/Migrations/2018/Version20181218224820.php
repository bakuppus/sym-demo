<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20181218224820 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE super_admins (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6F0391B6D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_super_admin (super_admin_id INT UNSIGNED NOT NULL, role_id INT UNSIGNED NOT NULL, INDEX IDX_8EDB7F0ABBF91D3B (super_admin_id), INDEX IDX_8EDB7F0AD60322AC (role_id), PRIMARY KEY(super_admin_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_super_admin ADD CONSTRAINT FK_8EDB7F0ABBF91D3B FOREIGN KEY (super_admin_id) REFERENCES super_admins (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_super_admin ADD CONSTRAINT FK_8EDB7F0AD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE role_super_admin DROP FOREIGN KEY FK_8EDB7F0ABBF91D3B');
        $this->addSql('DROP TABLE super_admins');
        $this->addSql('DROP TABLE role_super_admin');
    }
}
