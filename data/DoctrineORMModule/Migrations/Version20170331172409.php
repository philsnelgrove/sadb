<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170331172409 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE post_dimension DROP CONSTRAINT fk_6bf5bf6be1e19da5');
        $this->addSql('DROP INDEX idx_6bf5bf6be1e19da5');
        $this->addSql('ALTER TABLE post_dimension RENAME COLUMN social_media_presence_id TO post_id');
        $this->addSql('ALTER TABLE post_dimension ADD CONSTRAINT FK_6BF5BF6B4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6BF5BF6B4B89032C ON post_dimension (post_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post_dimension DROP CONSTRAINT FK_6BF5BF6B4B89032C');
        $this->addSql('DROP INDEX IDX_6BF5BF6B4B89032C');
        $this->addSql('ALTER TABLE post_dimension RENAME COLUMN post_id TO social_media_presence_id');
        $this->addSql('ALTER TABLE post_dimension ADD CONSTRAINT fk_6bf5bf6be1e19da5 FOREIGN KEY (social_media_presence_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6bf5bf6be1e19da5 ON post_dimension (social_media_presence_id)');
    }
}
