<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222223720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_measurements (id SERIAL NOT NULL, product_id INT NOT NULL, weight INT NOT NULL, length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16B5988FC6570731 ON products_measurements (product_id)');
        $this->addSql('ALTER TABLE products_measurements ADD CONSTRAINT FK_16B5988FC6570731 FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE products_measurements DROP CONSTRAINT FK_16B5988FC6570731');
        $this->addSql('DROP TABLE products_measurements');
    }
}
