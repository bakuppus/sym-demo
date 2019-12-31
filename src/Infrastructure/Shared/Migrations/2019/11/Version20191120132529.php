<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191120132529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fees (id INT UNSIGNED AUTO_INCREMENT NOT NULL, fee_unit_id INT UNSIGNED DEFAULT NULL, membership_id INT UNSIGNED DEFAULT NULL, vat INT DEFAULT 0 NOT NULL, price INT DEFAULT 0 NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A093C16CD17F50A6 (uuid), INDEX IDX_A093C16C1B0BA851 (fee_unit_id), INDEX IDX_A093C16C1FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fees ADD CONSTRAINT FK_A093C16C1B0BA851 FOREIGN KEY (fee_unit_id) REFERENCES fee_units (id)');
        $this->addSql('ALTER TABLE fees ADD CONSTRAINT FK_A093C16C1FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fees');
    }
}