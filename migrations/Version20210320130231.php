<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320130231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, teacher_id INT DEFAULT NULL, subject_code VARCHAR(255) NOT NULL, course_title VARCHAR(255) NOT NULL, credit_count INT NOT NULL, course_language VARCHAR(255) NOT NULL, course_level_and_year_of_study VARCHAR(255) NOT NULL, course_recomendation VARCHAR(255) NOT NULL, course_content LONGTEXT NOT NULL, course_aims LONGTEXT NOT NULL, INDEX IDX_169E6FB941807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_sheduling (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, day VARCHAR(255) NOT NULL, time VARCHAR(255) NOT NULL, room VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, capacity INT NOT NULL, INDEX IDX_CB0F5B6B591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reading (id INT AUTO_INCREMENT NOT NULL, reading_type VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, isbn VARCHAR(255) NOT NULL, library_link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reading_course (reading_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_8B2029D5527275CD (reading_id), INDEX IDX_8B2029D5591CC992 (course_id), PRIMARY KEY(reading_id, course_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, external_id INT NOT NULL, email VARCHAR(255) NOT NULL, office_number VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE course_sheduling ADD CONSTRAINT FK_CB0F5B6B591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE reading_course ADD CONSTRAINT FK_8B2029D5527275CD FOREIGN KEY (reading_id) REFERENCES reading (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reading_course ADD CONSTRAINT FK_8B2029D5591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_sheduling DROP FOREIGN KEY FK_CB0F5B6B591CC992');
        $this->addSql('ALTER TABLE reading_course DROP FOREIGN KEY FK_8B2029D5591CC992');
        $this->addSql('ALTER TABLE reading_course DROP FOREIGN KEY FK_8B2029D5527275CD');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_sheduling');
        $this->addSql('DROP TABLE reading');
        $this->addSql('DROP TABLE reading_course');
        $this->addSql('DROP TABLE teacher');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
