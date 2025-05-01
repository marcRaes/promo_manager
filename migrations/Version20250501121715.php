<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501121715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table Code';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE code (id SERIAL NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, discount DOUBLE PRECISION NOT NULL, domain_name VARCHAR(255) NOT NULL, valid_from DATE NOT NULL, valid_until DATE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_77153098A76ED395 ON code (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE code ADD CONSTRAINT FK_77153098A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE code DROP CONSTRAINT FK_77153098A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE code
        SQL);
    }
}
