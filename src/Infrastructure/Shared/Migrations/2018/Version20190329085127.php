<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190329085127 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin_golf_club (admin_id INT UNSIGNED NOT NULL, golf_club_id INT UNSIGNED NOT NULL, INDEX IDX_8F653A44642B8210 (admin_id), INDEX IDX_8F653A4470E209E0 (golf_club_id), PRIMARY KEY(admin_id, golf_club_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_golf_club ADD CONSTRAINT FK_8F653A44642B8210 FOREIGN KEY (admin_id) REFERENCES admins (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_golf_club ADD CONSTRAINT FK_8F653A4470E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE admin_golf_club');
    }
}
