<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261023144150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pause (id INT AUTO_INCREMENT NOT NULL, start_time DATE NOT NULL, end_time DATE NOT NULL, workday_id INT NOT NULL, INDEX IDX_D79A92EDAB01D695 (workday_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE workday (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, start_time DATE NOT NULL, end_time DATE DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pause ADD CONSTRAINT FK_D79A92EDAB01D695 FOREIGN KEY (workday_id) REFERENCES workday (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pause DROP FOREIGN KEY FK_D79A92EDAB01D695');
        $this->addSql('DROP TABLE pause');
        $this->addSql('DROP TABLE workday');
    }
}
