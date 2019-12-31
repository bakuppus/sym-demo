<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190227104835 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_courses ADD guest_booking_span INT NOT NULL, ADD member_booking_span INT NOT NULL, DROP tee_time_creation_status, DROP is_guest_booking_span, DROP is_member_booking_span, DROP is_pay_and_play');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_courses ADD tee_time_creation_status INT UNSIGNED NOT NULL, ADD is_guest_booking_span TINYINT(1) NOT NULL, ADD is_member_booking_span TINYINT(1) NOT NULL, ADD is_pay_and_play TINYINT(1) NOT NULL, DROP guest_booking_span, DROP member_booking_span');
    }
}
