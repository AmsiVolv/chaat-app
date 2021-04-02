<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402181923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE preparatory_courses (id INT AUTO_INCREMENT NOT NULL, subject_title VARCHAR(255) NOT NULL, subject_link VARCHAR(255) NOT NULL, preparatory_course_scope VARCHAR(255) NOT NULL, preparatory_course_date VARCHAR(255) NOT NULL, preparatory_course_price VARCHAR(255) NOT NULL, preparatory_course_contact_person_email VARCHAR(255) NOT NULL, preparatory_course_contact_person_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE preparatory_courses');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
