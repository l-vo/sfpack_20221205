<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221206111546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE movie ADD COLUMN slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, poster, country, released, price FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(2) NOT NULL, released DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price NUMERIC(4, 2) NOT NULL)');
        $this->addSql('INSERT INTO movie (id, title, poster, country, released, price) SELECT id, title, poster, country, released, price FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }
}
