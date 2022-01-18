<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116185327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_types (product_id INT NOT NULL, type_id INT NOT NULL, PRIMARY KEY(product_id, type_id))');
        $this->addSql('CREATE INDEX IDX_13675884584665A ON product_types (product_id)');
        $this->addSql('CREATE INDEX IDX_1367588C54C8C93 ON product_types (type_id)');
        $this->addSql('ALTER TABLE product_types ADD CONSTRAINT FK_13675884584665A FOREIGN KEY (product_id) REFERENCES "products" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_types ADD CONSTRAINT FK_1367588C54C8C93 FOREIGN KEY (type_id) REFERENCES "types" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE product_types');
    }
}
