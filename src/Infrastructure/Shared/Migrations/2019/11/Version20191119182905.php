<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191119182905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promotion_coupons (id INT UNSIGNED AUTO_INCREMENT NOT NULL, promotion_id INT UNSIGNED DEFAULT NULL, code VARCHAR(255) NOT NULL, usage_limit INT DEFAULT NULL, used INT NOT NULL, expires_at DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_AD19E722D17F50A6 (uuid), INDEX IDX_AD19E722139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, membership_id INT UNSIGNED DEFAULT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, priority INT NOT NULL, exclusive TINYINT(1) DEFAULT \'0\' NOT NULL, usage_limit INT DEFAULT NULL, used INT DEFAULT 0 NOT NULL, starts_at DATETIME DEFAULT NULL, ends_at DATETIME DEFAULT NULL, coupon_based TINYINT(1) DEFAULT \'0\' NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_EA1B303477153098 (code), UNIQUE INDEX UNIQ_EA1B3034D17F50A6 (uuid), INDEX IDX_EA1B30341FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_rules (id INT UNSIGNED AUTO_INCREMENT NOT NULL, promotion_id INT UNSIGNED DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_E99CED61D17F50A6 (uuid), INDEX IDX_E99CED61139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_actions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, promotion_id INT UNSIGNED DEFAULT NULL, type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_5D3507D5D17F50A6 (uuid), INDEX IDX_5D3507D5139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_tee_time_booking (tee_time_booking_id INT UNSIGNED NOT NULL, promotion_id INT UNSIGNED NOT NULL, INDEX IDX_90F6AA84D51EEBBA (tee_time_booking_id), INDEX IDX_90F6AA84139DF194 (promotion_id), PRIMARY KEY(tee_time_booking_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_coupons ADD CONSTRAINT FK_AD19E722139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id)');
        $this->addSql('ALTER TABLE promotions ADD CONSTRAINT FK_EA1B30341FB354CD FOREIGN KEY (membership_id) REFERENCES memberships (id)');
        $this->addSql('ALTER TABLE promotion_rules ADD CONSTRAINT FK_E99CED61139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id)');
        $this->addSql('ALTER TABLE promotion_actions ADD CONSTRAINT FK_5D3507D5139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id)');
        $this->addSql('ALTER TABLE promotion_tee_time_booking ADD CONSTRAINT FK_90F6AA84D51EEBBA FOREIGN KEY (tee_time_booking_id) REFERENCES tee_time_bookings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_tee_time_booking ADD CONSTRAINT FK_90F6AA84139DF194 FOREIGN KEY (promotion_id) REFERENCES promotions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_memberships ADD duration_type VARCHAR(255) DEFAULT NULL, CHANGE active_to active_to DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE memberships ADD total INT DEFAULT 0 NOT NULL, ADD duration_options LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD is_active TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_git_sync TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_hidden TINYINT(1) DEFAULT \'0\' NOT NULL, ADD play_right_only TINYINT(1) DEFAULT \'0\' NOT NULL, ADD state VARCHAR(255) DEFAULT \'draft\' NOT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promotion_coupons DROP FOREIGN KEY FK_AD19E722139DF194');
        $this->addSql('ALTER TABLE promotion_rules DROP FOREIGN KEY FK_E99CED61139DF194');
        $this->addSql('ALTER TABLE promotion_actions DROP FOREIGN KEY FK_5D3507D5139DF194');
        $this->addSql('ALTER TABLE promotion_tee_time_booking DROP FOREIGN KEY FK_90F6AA84139DF194');
        $this->addSql('DROP TABLE promotion_coupons');
        $this->addSql('DROP TABLE promotions');
        $this->addSql('DROP TABLE promotion_rules');
        $this->addSql('DROP TABLE promotion_actions');
        $this->addSql('DROP TABLE promotion_tee_time_booking');
        $this->addSql('ALTER TABLE memberships DROP total, DROP duration_options, DROP is_active, DROP is_git_sync, DROP is_hidden, DROP play_right_only, DROP state, CHANGE name name VARCHAR(150) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE player_memberships DROP duration_type, CHANGE active_to active_to DATETIME NOT NULL');
    }
}
