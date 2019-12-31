<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191121084037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_EA1B303477153098 ON promotions');
        $this->addSql('ALTER TABLE promotions ADD club_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT FK_EA1B303461190A32 FOREIGN KEY (club_id) REFERENCES golf_clubs (id)');
        $this->addSql('CREATE INDEX IDX_EA1B303461190A32 ON promotions (club_id)');
        $this->addSql('ALTER TABLE memberships ADD code VARCHAR(255) NOT NULL, ADD version INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE memberships DROP code, DROP version');
        $this->addSql('ALTER TABLE promotions DROP FOREIGN KEY FK_EA1B303461190A32');
        $this->addSql('DROP INDEX IDX_EA1B303461190A32 ON promotions');
        $this->addSql('ALTER TABLE promotions DROP club_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA1B303477153098 ON promotions (code)');
    }
}
