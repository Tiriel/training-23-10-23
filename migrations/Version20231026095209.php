<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026095209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, title, isbn, cover, author, released_at, plot FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id BLOB NOT NULL --(DC2Type:uuid)
        , created_by_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(20) NOT NULL, cover VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, released_at DATE NOT NULL --(DC2Type:date_immutable)
        , plot CLOB NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_CBE5A331B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO book (id, title, isbn, cover, author, released_at, plot) SELECT id, title, isbn, cover, author, released_at, plot FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
        $this->addSql('CREATE INDEX IDX_CBE5A331B03A8386 ON book (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, title, isbn, author, released_at, plot, cover FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id BLOB NOT NULL --(DC2Type:uuid)
        , title VARCHAR(255) NOT NULL, isbn VARCHAR(20) NOT NULL, author VARCHAR(255) DEFAULT NULL, released_at DATE NOT NULL --(DC2Type:date_immutable)
        , plot CLOB NOT NULL, cover VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO book (id, title, isbn, author, released_at, plot, cover) SELECT id, title, isbn, author, released_at, plot, cover FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
    }
}
