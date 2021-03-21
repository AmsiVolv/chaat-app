<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320163653 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_teacher (course_id INT NOT NULL, teacher_id INT NOT NULL, INDEX IDX_B835A339591CC992 (course_id), INDEX IDX_B835A33941807E1D (teacher_id), PRIMARY KEY(course_id, teacher_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_teacher ADD CONSTRAINT FK_B835A339591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_teacher ADD CONSTRAINT FK_B835A33941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D');
        $this->addSql('DROP INDEX IDX_169E6FB941807E1D ON course');
        $this->addSql('ALTER TABLE course DROP teacher_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE course_teacher');
        $this->addSql('ALTER TABLE course ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB941807E1D ON course (teacher_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
