<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170421124451 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE post_dimension ADD values TEXT NOT NULL');
        $this->addSql('ALTER TABLE post_dimension DROP value');
        $this->addSql('COMMENT ON COLUMN post_dimension.values IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE page_dimension ADD values TEXT NOT NULL');
        $this->addSql('ALTER TABLE page_dimension DROP value');
        $this->addSql('COMMENT ON COLUMN page_dimension.values IS \'(DC2Type:array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE page_dimension ADD value VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE page_dimension DROP values');
        $this->addSql('ALTER TABLE post_dimension ADD value VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post_dimension DROP values');
    }
}
