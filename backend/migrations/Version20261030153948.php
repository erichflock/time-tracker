<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Change workday.date (and pause.workday_date FK) from DATE to VARCHAR(10) for string PK.
 */
final class Version20261030153948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change workday.date PK and pause.workday_date FK from DATE to VARCHAR(10)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pause DROP FOREIGN KEY FK_D79A92EDA0758353');
        $this->addSql('ALTER TABLE workday CHANGE date date VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE pause CHANGE workday_date workday_date VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE pause ADD CONSTRAINT FK_D79A92EDA0758353 FOREIGN KEY (workday_date) REFERENCES workday (date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pause DROP FOREIGN KEY FK_D79A92EDA0758353');
        $this->addSql('ALTER TABLE workday CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE pause CHANGE workday_date workday_date DATE NOT NULL');
        $this->addSql('ALTER TABLE pause ADD CONSTRAINT FK_D79A92EDA0758353 FOREIGN KEY (workday_date) REFERENCES workday (date)');
    }
}
