<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251226001241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "__EFMigrationsHistory"');
        $this->addSql('ALTER TABLE menu ADD prix DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "__EFMigrationsHistory" ("MigrationId" VARCHAR(150) NOT NULL, "ProductVersion" VARCHAR(32) NOT NULL, PRIMARY KEY ("MigrationId"))');
        $this->addSql('ALTER TABLE menu DROP prix');
    }
}
