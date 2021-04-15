<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415084057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_conversation ADD CONSTRAINT FK_66C86CD0BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES group_message (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_66C86CD0BA0E79C3 ON group_conversation (last_message_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_conversation DROP FOREIGN KEY FK_66C86CD0BA0E79C3');
        $this->addSql('DROP INDEX UNIQ_66C86CD0BA0E79C3 ON group_conversation');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
