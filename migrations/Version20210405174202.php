<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405174202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, school_area_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7D053A932F207204 (school_area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_meals (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, meal_name VARCHAR(255) NOT NULL, meal_content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7385C58DCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A932F207204 FOREIGN KEY (school_area_id) REFERENCES school_area (id)');
        $this->addSql('ALTER TABLE menu_meals ADD CONSTRAINT FK_7385C58DCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_meals DROP FOREIGN KEY FK_7385C58DCCD7E912');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_meals');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
