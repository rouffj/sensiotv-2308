<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210826082304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a new column user.roles';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, imdb_id, title, release_date, poster, plot, duration FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, imdb_id VARCHAR(15) NOT NULL COLLATE BINARY, title VARCHAR(255) NOT NULL COLLATE BINARY, release_date DATE NOT NULL, poster VARCHAR(255) DEFAULT NULL COLLATE BINARY, plot CLOB DEFAULT NULL COLLATE BINARY, duration VARCHAR(10) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO movie (id, imdb_id, title, release_date, poster, plot, duration) SELECT id, imdb_id, title, release_date, poster, plot, duration FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26F53B538EB ON movie (imdb_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN roles CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1D5EF26F53B538EB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, imdb_id, title, release_date, poster, plot, duration FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, imdb_id VARCHAR(15) NOT NULL, title VARCHAR(255) NOT NULL, release_date DATE NOT NULL, poster VARCHAR(255) DEFAULT NULL, plot CLOB DEFAULT NULL, duration VARCHAR(10) NOT NULL)');
        $this->addSql('INSERT INTO movie (id, imdb_id, title, release_date, poster, plot, duration) SELECT id, imdb_id, title, release_date, poster, plot, duration FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, first_name, last_name, email, phone, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(80) DEFAULT NULL, last_name VARCHAR(80) NOT NULL, email VARCHAR(200) NOT NULL, phone VARCHAR(15) DEFAULT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, first_name, last_name, email, phone, password) SELECT id, first_name, last_name, email, phone, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
