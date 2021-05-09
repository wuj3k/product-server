<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210509085814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product (id VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX name_index ON product (amount)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
    }
}
