<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261016165333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendence (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, check_in DATETIME DEFAULT NULL, check_out DATETIME DEFAULT NULL, employee_id INT NOT NULL, INDEX IDX_E2819AC68C03F15C (employee_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE day_off (id INT AUTO_INCREMENT NOT NULL, date DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE day_off_employee (day_off_id INT NOT NULL, employee_id INT NOT NULL, INDEX IDX_3BC545457E81C1E1 (day_off_id), INDEX IDX_3BC545458C03F15C (employee_id), PRIMARY KEY (day_off_id, employee_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, employee_number VARCHAR(50) NOT NULL, first_name VARCHAR(255) NOT NULL, work_start_time TIME NOT NULL, work_end_time TIME NOT NULL, last_name VARCHAR(255) NOT NULL, occupation VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE month (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, is_day_off_populated TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE weekly_day_off (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9A1CDE63E5A02990 (day), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attendence ADD CONSTRAINT FK_E2819AC68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE day_off_employee ADD CONSTRAINT FK_3BC545457E81C1E1 FOREIGN KEY (day_off_id) REFERENCES day_off (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE day_off_employee ADD CONSTRAINT FK_3BC545458C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendence DROP FOREIGN KEY FK_E2819AC68C03F15C');
        $this->addSql('ALTER TABLE day_off_employee DROP FOREIGN KEY FK_3BC545457E81C1E1');
        $this->addSql('ALTER TABLE day_off_employee DROP FOREIGN KEY FK_3BC545458C03F15C');
        $this->addSql('DROP TABLE attendence');
        $this->addSql('DROP TABLE day_off');
        $this->addSql('DROP TABLE day_off_employee');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE month');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE weekly_day_off');
    }
}
