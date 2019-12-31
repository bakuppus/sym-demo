<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190206123016 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players ADD home_club_id INT UNSIGNED DEFAULT NULL, ADD password VARCHAR(150) NOT NULL, ADD first_name VARCHAR(150) DEFAULT NULL, ADD last_name VARCHAR(150) DEFAULT NULL, ADD phone VARCHAR(150) NOT NULL, ADD golf_id VARCHAR(150) DEFAULT NULL, ADD active_membership TINYINT(1) NOT NULL, ADD hcp VARCHAR(150) DEFAULT NULL, ADD brain_tree_id VARCHAR(150) DEFAULT NULL, ADD pay_pal_email VARCHAR(150) DEFAULT NULL, ADD card_brand VARCHAR(150) DEFAULT NULL, ADD card_last_four VARCHAR(150) DEFAULT NULL, ADD trial_ends_at DATETIME DEFAULT NULL, ADD favorite_courses VARCHAR(150) DEFAULT NULL, ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD remember_token VARCHAR(255) DEFAULT NULL, CHANGE name email VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6D439C16A FOREIGN KEY (home_club_id) REFERENCES golf_clubs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_264E43A6E7927C74 ON players (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_264E43A6444F97DD ON players (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_264E43A6D17F50A6 ON players (uuid)');
        $this->addSql('CREATE INDEX IDX_264E43A6D439C16A ON players (home_club_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_264E43A6D439C16A');
        $this->addSql('DROP INDEX UNIQ_264E43A6E7927C74 ON players');
        $this->addSql('DROP INDEX UNIQ_264E43A6444F97DD ON players');
        $this->addSql('DROP INDEX UNIQ_264E43A6D17F50A6 ON players');
        $this->addSql('DROP INDEX IDX_264E43A6D439C16A ON players');
        $this->addSql('ALTER TABLE players ADD name VARCHAR(150) NOT NULL COLLATE utf8mb4_unicode_ci, DROP home_club_id, DROP email, DROP password, DROP first_name, DROP last_name, DROP phone, DROP golf_id, DROP active_membership, DROP hcp, DROP brain_tree_id, DROP pay_pal_email, DROP card_brand, DROP card_last_four, DROP trial_ends_at, DROP favorite_courses, DROP uuid, DROP remember_token');
    }
}
