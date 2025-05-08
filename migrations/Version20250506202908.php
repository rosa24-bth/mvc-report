<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506202908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__longterm_economic_support AS SELECT id, year, value FROM longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE longterm_economic_support (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, year INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, "group" VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO longterm_economic_support (id, year, value) SELECT id, year, value FROM __temp__longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__low_economic_standard AS SELECT id, year, value FROM low_economic_standard
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE low_economic_standard
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE low_economic_standard (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, year INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, "group" VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO low_economic_standard (id, year, value) SELECT id, year, value FROM __temp__low_economic_standard
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__low_economic_standard
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__longterm_economic_support AS SELECT id, year, value FROM longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE longterm_economic_support (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, year INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, sex VARCHAR(50) NOT NULL, age_group VARCHAR(50) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO longterm_economic_support (id, year, value) SELECT id, year, value FROM __temp__longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__longterm_economic_support
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__low_economic_standard AS SELECT id, year, value FROM low_economic_standard
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE low_economic_standard
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE low_economic_standard (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, year INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, sex VARCHAR(50) NOT NULL, age_group VARCHAR(50) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO low_economic_standard (id, year, value) SELECT id, year, value FROM __temp__low_economic_standard
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__low_economic_standard
        SQL);
    }
}
