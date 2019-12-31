<?php

namespace App\Infrastructure\Shared\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20190125114108 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX FK_NKUQ14YKIP ON golf_courses (golf_club_id, name)');
        $this->addSql('ALTER TABLE memberships DROP FOREIGN KEY FK_865A477670E209E0');
        $this->addSql('DROP INDEX fk_golfclub_name ON memberships');
        $this->addSql('CREATE UNIQUE INDEX FK_NKUQ14YKIP ON memberships (golf_club_id, name)');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A477670E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX FK_NKUQ14YKIP ON golf_courses');
        $this->addSql('ALTER TABLE memberships DROP FOREIGN KEY FK_865A477670E209E0');
        $this->addSql('DROP INDEX fk_nkuq14ykip ON memberships');
        $this->addSql('CREATE UNIQUE INDEX fk_golfclub_name ON memberships (golf_club_id, name)');
        $this->addSql('ALTER TABLE memberships ADD CONSTRAINT FK_865A477670E209E0 FOREIGN KEY (golf_club_id) REFERENCES golf_clubs (id)');
    }
}
