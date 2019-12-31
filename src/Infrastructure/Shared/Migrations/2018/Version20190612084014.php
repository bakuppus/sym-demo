<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190612084014 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C99E6F5DF');
        $this->addSql('DROP INDEX IDX_EAA81A4C99E6F5DF ON transactions');
        $this->addSql('ALTER TABLE transactions CHANGE player_id participant_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9D1C3019 FOREIGN KEY (participant_id) REFERENCES tee_time_booking_participants (id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C9D1C3019 ON transactions (participant_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C9D1C3019');
        $this->addSql('DROP INDEX IDX_EAA81A4C9D1C3019 ON transactions');
        $this->addSql('ALTER TABLE transactions CHANGE participant_id player_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_EAA81A4C99E6F5DF ON transactions (player_id)');
    }
}
