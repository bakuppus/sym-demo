<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204095615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB07597D3FE');
        $this->addSql('DROP INDEX IDX_62809DB07597D3FE ON order_items');
        $this->addSql('ALTER TABLE order_items CHANGE member_id membership_card_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB0C3610C71 FOREIGN KEY (membership_card_id) REFERENCES player_memberships (id)');
        $this->addSql('CREATE INDEX IDX_62809DB0C3610C71 ON order_items (membership_card_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB0C3610C71');
        $this->addSql('DROP INDEX IDX_62809DB0C3610C71 ON order_items');
        $this->addSql('ALTER TABLE order_items CHANGE membership_card_id member_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB07597D3FE FOREIGN KEY (member_id) REFERENCES player_memberships (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_62809DB07597D3FE ON order_items (member_id)');
    }
}
