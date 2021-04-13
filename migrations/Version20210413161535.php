<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413161535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_conversation (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, group_name VARCHAR(255) NOT NULL, group_color VARCHAR(255) NOT NULL, max_member_count INT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_66C86CD0591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_conversation_user (group_conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_84A89A69B73F9E4F (group_conversation_id), INDEX IDX_84A89A69A76ED395 (user_id), PRIMARY KEY(group_conversation_id, user_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, group_conversation_id INT NOT NULL, message_content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_30BD6473A76ED395 (user_id), INDEX IDX_30BD6473B73F9E4F (group_conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_conversation ADD CONSTRAINT FK_66C86CD0591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE group_conversation_user ADD CONSTRAINT FK_84A89A69B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_conversation_user ADD CONSTRAINT FK_84A89A69A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_conversation_user DROP FOREIGN KEY FK_84A89A69B73F9E4F');
        $this->addSql('ALTER TABLE group_message DROP FOREIGN KEY FK_30BD6473B73F9E4F');
        $this->addSql('DROP TABLE group_conversation');
        $this->addSql('DROP TABLE group_conversation_user');
        $this->addSql('DROP TABLE group_message');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
