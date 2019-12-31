<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190208115906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_courses ADD is_use_custom_information TINYINT(1) NOT NULL, ADD is_active TINYINT(1) NOT NULL, ADD is_use_dynamic_pricing TINYINT(1) NOT NULL, ADD is_guest_booking_span TINYINT(1) NOT NULL, ADD is_member_booking_span TINYINT(1) NOT NULL, ADD is_admin_teetime_status TINYINT(1) NOT NULL, ADD is_pay_and_play TINYINT(1) NOT NULL, DROP use_custom_information, DROP active, DROP use_dynamic_pricing, DROP guest_booking_span, DROP member_booking_span, DROP admin_teetime_status, DROP pay_and_play');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE golf_courses ADD use_custom_information TINYINT(1) NOT NULL, ADD active TINYINT(1) NOT NULL, ADD use_dynamic_pricing TINYINT(1) NOT NULL, ADD guest_booking_span TINYINT(1) NOT NULL, ADD member_booking_span TINYINT(1) NOT NULL, ADD admin_teetime_status TINYINT(1) NOT NULL, ADD pay_and_play TINYINT(1) NOT NULL, DROP is_use_custom_information, DROP is_active, DROP is_use_dynamic_pricing, DROP is_guest_booking_span, DROP is_member_booking_span, DROP is_admin_teetime_status, DROP is_pay_and_play');
    }
}
