<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011084923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE feed_parser_job (
            id SERIAL NOT NULL, 
            title TEXT NOT NULL, 
            description TEXT NOT NULL, 
            url VARCHAR(5000) NOT NULL, 
            publish_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            options JSONB DEFAULT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX job_url_idx ON feed_parser_job(url)');


        $this->addSql('CREATE TABLE feed_parser_job_position (
            id bigint NOT NULL, 
            PRIMARY KEY(id)
        )');

        $this->addSql('INSERT INTO feed_parser_job_position (id) VALUES (0);');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE feed_parser_job');
        $this->addSql('DROP TABLE feed_parser_job_position');
    }
}
