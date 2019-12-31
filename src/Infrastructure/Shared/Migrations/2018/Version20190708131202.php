<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190708131202 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players CHANGE `is_not_registered_yet` `is_registered` tinyint(1) default 1 not null;');
        $this->addSql('UPDATE players SET is_registered = CASE WHEN is_registered = 1 THEN 0 ELSE 1 END');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE players CHANGE `is_registered` `is_not_registered_yet` tinyint(1) default 0 not null;');
        $this->addSql('UPDATE players SET is_not_registered_yet = CASE WHEN is_not_registered_yet = 1 THEN 0 ELSE 1 END');
    }
}
