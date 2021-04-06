<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320131707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD course_recommendation VARCHAR(255) DEFAULT NULL, ADD course_url VARCHAR(255) DEFAULT NULL, DROP course_recomendation, CHANGE course_title course_title VARCHAR(255) DEFAULT NULL, CHANGE credit_count credit_count INT DEFAULT NULL, CHANGE course_language course_language VARCHAR(255) DEFAULT NULL, CHANGE course_level_and_year_of_study course_level_and_year_of_study VARCHAR(255) DEFAULT NULL, CHANGE course_content course_content LONGTEXT DEFAULT NULL, CHANGE course_aims course_aims LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD course_recomendation VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, DROP course_recommendation, DROP course_url, CHANGE course_title course_title VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE credit_count credit_count INT NOT NULL, CHANGE course_language course_language VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE course_level_and_year_of_study course_level_and_year_of_study VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE course_content course_content LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE course_aims course_aims LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
