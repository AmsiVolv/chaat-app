<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210403095806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Study programs';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE study_program (id INT AUTO_INCREMENT NOT NULL, faculty_id INT NOT NULL, study_program_language_id INT NOT NULL, study_program_form_id INT DEFAULT NULL, INDEX IDX_A7F88AE6680CAB68 (faculty_id), INDEX IDX_A7F88AE66DF4E051 (study_program_language_id), INDEX IDX_A7F88AE679A13BA8 (study_program_form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study_program_study_program_aim (study_program_id INT NOT NULL, study_program_aim_id INT NOT NULL, INDEX IDX_59704120B924636A (study_program_id), INDEX IDX_59704120389422AC (study_program_aim_id), PRIMARY KEY(study_program_id, study_program_aim_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study_program_aim (id INT AUTO_INCREMENT NOT NULL, aim VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study_program_form_type (id INT AUTO_INCREMENT NOT NULL, form VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study_program_language (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE study_program ADD CONSTRAINT FK_A7F88AE6680CAB68 FOREIGN KEY (faculty_id) REFERENCES faculty (id)');
        $this->addSql('ALTER TABLE study_program ADD CONSTRAINT FK_A7F88AE66DF4E051 FOREIGN KEY (study_program_language_id) REFERENCES study_program_language (id)');
        $this->addSql('ALTER TABLE study_program ADD CONSTRAINT FK_A7F88AE679A13BA8 FOREIGN KEY (study_program_form_id) REFERENCES study_program_form_type (id)');
        $this->addSql('ALTER TABLE study_program_study_program_aim ADD CONSTRAINT FK_59704120B924636A FOREIGN KEY (study_program_id) REFERENCES study_program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE study_program_study_program_aim ADD CONSTRAINT FK_59704120389422AC FOREIGN KEY (study_program_aim_id) REFERENCES study_program_aim (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE study_program_study_program_aim DROP FOREIGN KEY FK_59704120B924636A');
        $this->addSql('ALTER TABLE study_program_study_program_aim DROP FOREIGN KEY FK_59704120389422AC');
        $this->addSql('ALTER TABLE study_program DROP FOREIGN KEY FK_A7F88AE679A13BA8');
        $this->addSql('ALTER TABLE study_program DROP FOREIGN KEY FK_A7F88AE66DF4E051');
        $this->addSql('DROP TABLE study_program');
        $this->addSql('DROP TABLE study_program_study_program_aim');
        $this->addSql('DROP TABLE study_program_aim');
        $this->addSql('DROP TABLE study_program_form_type');
        $this->addSql('DROP TABLE study_program_language');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
