<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190712085047 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('failed_jobs');

        $table->addColumn('id', Type::BIGINT)->setAutoincrement(true)->setUnsigned(true);
        $table->addColumn('connection', Type::TEXT);
        $table->addColumn('queue', Type::TEXT);
        $table->addColumn('payload', Type::TEXT);
        $table->addColumn('exception', Type::TEXT);
        $table->addColumn('failed_at', Type::DATETIME)->setDefault('CURRENT_TIMESTAMP');

        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('failed_jobs');
    }
}
