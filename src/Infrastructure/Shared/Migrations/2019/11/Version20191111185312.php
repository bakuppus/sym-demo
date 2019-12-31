<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111185312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_methods (id INT UNSIGNED AUTO_INCREMENT NOT NULL, gateway_config_id INT UNSIGNED DEFAULT NULL, code VARCHAR(255) NOT NULL, environment VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FABF983D17F50A6 (uuid), INDEX IDX_4FABF983F23D6140 (gateway_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_tokens (hash VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', after_url LONGTEXT DEFAULT NULL, target_url LONGTEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit_cards (id INT UNSIGNED AUTO_INCREMENT NOT NULL, customer_id INT UNSIGNED DEFAULT NULL, token VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, last_four VARCHAR(4) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5CADD653D17F50A6 (uuid), INDEX IDX_5CADD6539395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED DEFAULT NULL, method_id INT UNSIGNED DEFAULT NULL, credit_card_id INT UNSIGNED DEFAULT NULL, currency_code VARCHAR(3) NOT NULL, amount INT NOT NULL, state VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_65D29B32D17F50A6 (uuid), INDEX IDX_65D29B328D9F6D38 (order_id), INDEX IDX_65D29B3219883967 (method_id), INDEX IDX_65D29B327048FD0F (credit_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gateway_configs (id INT UNSIGNED AUTO_INCREMENT NOT NULL, gateway_name VARCHAR(255) NOT NULL, factory_name VARCHAR(255) NOT NULL, config JSON NOT NULL COMMENT \'(DC2Type:json_array)\', uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_33BD31DCD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_methods ADD CONSTRAINT FK_4FABF983F23D6140 FOREIGN KEY (gateway_config_id) REFERENCES gateway_configs (id)');
        $this->addSql('ALTER TABLE credit_cards ADD CONSTRAINT FK_5CADD6539395C3F3 FOREIGN KEY (customer_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B328D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B3219883967 FOREIGN KEY (method_id) REFERENCES payment_methods (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B327048FD0F FOREIGN KEY (credit_card_id) REFERENCES credit_cards (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B3219883967');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B327048FD0F');
        $this->addSql('ALTER TABLE payment_methods DROP FOREIGN KEY FK_4FABF983F23D6140');
        $this->addSql('DROP TABLE payment_methods');
        $this->addSql('DROP TABLE payment_tokens');
        $this->addSql('DROP TABLE credit_cards');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE gateway_configs');
    }
}
