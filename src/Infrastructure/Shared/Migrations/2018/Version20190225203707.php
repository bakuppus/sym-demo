<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190225203707 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE memberships CHANGE friend_discount friend_discount SMALLINT DEFAULT 0, CHANGE name name VARCHAR(150) DEFAULT NULL, CHANGE owner_discount owner_discount SMALLINT DEFAULT 0');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE memberships CHANGE name name VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE owner_discount owner_discount SMALLINT DEFAULT 0 NOT NULL, CHANGE friend_discount friend_discount SMALLINT DEFAULT 0 NOT NULL');
    }
}
