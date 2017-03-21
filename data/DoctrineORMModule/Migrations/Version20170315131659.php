<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170315131659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE access_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE enterprise_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guest_access_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE social_media_gateway_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE social_media_presence_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_dimension_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE dimension_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE access_token (id INT NOT NULL, social_media_gateway_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6A2DD6829F4BF59 ON access_token (social_media_gateway_id)');
        $this->addSql('CREATE TABLE enterprise (id INT NOT NULL, name VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE guest_access_token (id INT NOT NULL, social_media_gateway_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B5930ADF29F4BF59 ON guest_access_token (social_media_gateway_id)');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, social_media_presence_id INT DEFAULT NULL, type_id INT DEFAULT NULL, social_media_service_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, raw_text_content VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DE1E19DA5 ON post (social_media_presence_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8DC54C8C93 ON post (type_id)');
        $this->addSql('CREATE TABLE social_media_gateway (id INT NOT NULL, access_token_id INT DEFAULT NULL, social_media_presence_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C393D5A22CCB2688 ON social_media_gateway (access_token_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C393D5A2E1E19DA5 ON social_media_gateway (social_media_presence_id)');
        $this->addSql('CREATE TABLE social_media_presence (id INT NOT NULL, social_media_gateway_id INT DEFAULT NULL, parent_enterprise_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_91C204C429F4BF59 ON social_media_presence (social_media_gateway_id)');
        $this->addSql('CREATE INDEX IDX_91C204C475FB7951 ON social_media_presence (parent_enterprise_id)');
        $this->addSql('CREATE TABLE users (user_id INT NOT NULL, enterprise_id INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, display_name VARCHAR(50) DEFAULT NULL, password VARCHAR(128) NOT NULL, state SMALLINT DEFAULT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX IDX_1483A5E9A97D1AC3 ON users (enterprise_id)');
        $this->addSql('CREATE TABLE post_data (id INT NOT NULL, social_media_presence_id INT DEFAULT NULL, social_media_service_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, metadata VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64A69468E1E19DA5 ON post_data (social_media_presence_id)');
        $this->addSql('CREATE TABLE post_dimension (id INT NOT NULL, value VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, social_media_presence_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6BF5BF6BE1E19DA5 ON post_dimension (social_media_presence_id)');
        $this->addSql('CREATE TABLE dimension (id INT NOT NULL, name VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE post_type (id INT NOT NULL, type VARCHAR(255) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (user_id INT NOT NULL, username VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, display_name VARCHAR(50) DEFAULT NULL, password VARCHAR(128) NOT NULL, state SMALLINT DEFAULT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD6829F4BF59 FOREIGN KEY (social_media_gateway_id) REFERENCES social_media_gateway (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE guest_access_token ADD CONSTRAINT FK_B5930ADF29F4BF59 FOREIGN KEY (social_media_gateway_id) REFERENCES social_media_gateway (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DE1E19DA5 FOREIGN KEY (social_media_presence_id) REFERENCES social_media_presence (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DC54C8C93 FOREIGN KEY (type_id) REFERENCES post_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE social_media_gateway ADD CONSTRAINT FK_C393D5A22CCB2688 FOREIGN KEY (access_token_id) REFERENCES access_token (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE social_media_gateway ADD CONSTRAINT FK_C393D5A2E1E19DA5 FOREIGN KEY (social_media_presence_id) REFERENCES social_media_presence (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE social_media_presence ADD CONSTRAINT FK_91C204C429F4BF59 FOREIGN KEY (social_media_gateway_id) REFERENCES social_media_gateway (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE social_media_presence ADD CONSTRAINT FK_91C204C475FB7951 FOREIGN KEY (parent_enterprise_id) REFERENCES enterprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9A97D1AC3 FOREIGN KEY (enterprise_id) REFERENCES enterprise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_data ADD CONSTRAINT FK_64A69468E1E19DA5 FOREIGN KEY (social_media_presence_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    
        
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE social_media_gateway DROP CONSTRAINT FK_C393D5A22CCB2688');
        $this->addSql('ALTER TABLE social_media_presence DROP CONSTRAINT FK_91C204C475FB7951');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9A97D1AC3');
        $this->addSql('ALTER TABLE post_data DROP CONSTRAINT FK_64A69468E1E19DA5');
        $this->addSql('ALTER TABLE access_token DROP CONSTRAINT FK_B6A2DD6829F4BF59');
        $this->addSql('ALTER TABLE guest_access_token DROP CONSTRAINT FK_B5930ADF29F4BF59');
        $this->addSql('ALTER TABLE social_media_presence DROP CONSTRAINT FK_91C204C429F4BF59');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DE1E19DA5');
        $this->addSql('ALTER TABLE social_media_gateway DROP CONSTRAINT FK_C393D5A2E1E19DA5');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DC54C8C93');
        $this->addSql('DROP SEQUENCE access_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE enterprise_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guest_access_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE social_media_gateway_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE social_media_presence_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_data_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_dimension_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE dimension_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_user_id_seq CASCADE');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE enterprise');
        $this->addSql('DROP TABLE guest_access_token');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE social_media_gateway');
        $this->addSql('DROP TABLE social_media_presence');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE post_data');
        $this->addSql('DROP TABLE post_dimension');
        $this->addSql('DROP TABLE dimension');
        $this->addSql('DROP TABLE post_type');
        $this->addSql('DROP TABLE "user"');
    }
    
    public function add_dimensions()
    {
        // import dimensions file
    }
}