<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530155244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_A45BDDC17E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, owner_id, title, description, base_url FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, base_url VARCHAR(255) NOT NULL, CONSTRAINT FK_A45BDDC17E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO application (id, owner_id, title, description, base_url) SELECT id, owner_id, title, description, base_url FROM __temp__application');
        $this->addSql('DROP TABLE __temp__application');
        $this->addSql('CREATE INDEX IDX_A45BDDC17E3C61F9 ON application (owner_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__proxy_route AS SELECT id, pattern FROM proxy_route');
        $this->addSql('DROP TABLE proxy_route');
        $this->addSql('CREATE TABLE proxy_route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, application_id INTEGER NOT NULL, pattern VARCHAR(255) NOT NULL, CONSTRAINT FK_DBB88A543E030ACD FOREIGN KEY (application_id) REFERENCES application (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO proxy_route (id, pattern) SELECT id, pattern FROM __temp__proxy_route');
        $this->addSql('DROP TABLE __temp__proxy_route');
        $this->addSql('CREATE INDEX IDX_DBB88A543E030ACD ON proxy_route (application_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_A45BDDC17E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__application AS SELECT id, owner_id, title, description, base_url FROM application');
        $this->addSql('DROP TABLE application');
        $this->addSql('CREATE TABLE application (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, base_url VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO application (id, owner_id, title, description, base_url) SELECT id, owner_id, title, description, base_url FROM __temp__application');
        $this->addSql('DROP TABLE __temp__application');
        $this->addSql('CREATE INDEX IDX_A45BDDC17E3C61F9 ON application (owner_id)');
        $this->addSql('DROP INDEX IDX_DBB88A543E030ACD');
        $this->addSql('CREATE TEMPORARY TABLE __temp__proxy_route AS SELECT id, pattern FROM proxy_route');
        $this->addSql('DROP TABLE proxy_route');
        $this->addSql('CREATE TABLE proxy_route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pattern VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO proxy_route (id, pattern) SELECT id, pattern FROM __temp__proxy_route');
        $this->addSql('DROP TABLE __temp__proxy_route');
    }
}
