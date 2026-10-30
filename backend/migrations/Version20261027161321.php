<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Switch Workday primary key from id to date, and update Pause FK accordingly.
 */
final class Version20261027161321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Use workday.date as primary key and reference it from pause';
    }

    public function up(Schema $schema): void
    {
        // 1. Drop the old FK first
        $this->addSql('ALTER TABLE pause DROP FOREIGN KEY FK_D79A92EDAB01D695');
        $this->addSql('DROP INDEX IDX_D79A92EDAB01D695 ON pause');

        // 2. Promote date to PK before any FK can reference it
        $this->addSql('ALTER TABLE workday MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE workday DROP PRIMARY KEY, DROP id, ADD PRIMARY KEY (date)');

        // 3. Replace pause.workday_id with workday_date and add the new FK
        $this->addSql('ALTER TABLE pause ADD workday_date DATE NOT NULL, DROP workday_id');
        $this->addSql('ALTER TABLE pause ADD CONSTRAINT FK_D79A92EDA0758353 FOREIGN KEY (workday_date) REFERENCES workday (date)');
        $this->addSql('CREATE INDEX IDX_D79A92EDA0758353 ON pause (workday_date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pause DROP FOREIGN KEY FK_D79A92EDA0758353');
        $this->addSql('DROP INDEX IDX_D79A92EDA0758353 ON pause');
        $this->addSql('ALTER TABLE pause ADD workday_id INT NOT NULL, DROP workday_date');

        $this->addSql('ALTER TABLE workday ADD id INT AUTO_INCREMENT NOT NULL FIRST, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');

        $this->addSql('ALTER TABLE pause ADD CONSTRAINT FK_D79A92EDAB01D695 FOREIGN KEY (workday_id) REFERENCES workday (id)');
        $this->addSql('CREATE INDEX IDX_D79A92EDAB01D695 ON pause (workday_id)');
    }
}
