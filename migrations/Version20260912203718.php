<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260912203718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE day_off (id INT AUTO_INCREMENT NOT NULL, date DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE day_off_employee (day_off_id INT NOT NULL, employee_id INT NOT NULL, INDEX IDX_3BC545457E81C1E1 (day_off_id), INDEX IDX_3BC545458C03F15C (employee_id), PRIMARY KEY (day_off_id, employee_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE month (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, is_day_off_populated TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE weekly_day_off (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE day_off_employee ADD CONSTRAINT FK_3BC545457E81C1E1 FOREIGN KEY (day_off_id) REFERENCES day_off (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE day_off_employee ADD CONSTRAINT FK_3BC545458C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee ADD working_hours INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE day_off_employee DROP FOREIGN KEY FK_3BC545457E81C1E1');
        $this->addSql('ALTER TABLE day_off_employee DROP FOREIGN KEY FK_3BC545458C03F15C');
        $this->addSql('DROP TABLE day_off');
        $this->addSql('DROP TABLE day_off_employee');
        $this->addSql('DROP TABLE month');
        $this->addSql('DROP TABLE weekly_day_off');
        $this->addSql('ALTER TABLE employee DROP working_hours');
    }
}
