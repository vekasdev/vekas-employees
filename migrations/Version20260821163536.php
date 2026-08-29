<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260821163536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendence (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, check_in DATETIME NOT NULL, check_out DATETIME NOT NULL, employee_id INT NOT NULL, INDEX IDX_E2819AC68C03F15C (employee_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, employee_number VARCHAR(50) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attendence ADD CONSTRAINT FK_E2819AC68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendence DROP FOREIGN KEY FK_E2819AC68C03F15C');
        $this->addSql('DROP TABLE attendence');
        $this->addSql('DROP TABLE employee');
    }
}
