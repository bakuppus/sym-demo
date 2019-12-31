<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209112336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_memberships ADD order_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE player_memberships ADD CONSTRAINT FK_DE6DAEE38D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DE6DAEE38D9F6D38 ON player_memberships (order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_memberships DROP FOREIGN KEY FK_DE6DAEE38D9F6D38');
        $this->addSql('DROP INDEX UNIQ_DE6DAEE38D9F6D38 ON player_memberships');
        $this->addSql('ALTER TABLE player_memberships DROP order_id');
    }
}
