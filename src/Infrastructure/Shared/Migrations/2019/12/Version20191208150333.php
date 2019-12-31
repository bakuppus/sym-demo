<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191208150333 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('UPDATE memberships SET duration_options = "a:0:{}"');
    }

    public function down(Schema $schema) : void
    {
    }
}
