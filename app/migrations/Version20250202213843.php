<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202213843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "orders_products" (id SERIAL NOT NULL, order_id INT NOT NULL, product_id INT NOT NULL, amount INT NOT NULL, cost INT NOT NULL, tax INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_749C879C8D9F6D38 ON "orders_products" (order_id)');
        $this->addSql('CREATE INDEX IDX_749C879C4584665A ON "orders_products" (product_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_order_product_order_product ON "orders_products" (order_id, product_id)');
        $this->addSql('ALTER TABLE "orders_products" ADD CONSTRAINT FK_749C879C8D9F6D38 FOREIGN KEY (order_id) REFERENCES "orders" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "orders_products" ADD CONSTRAINT FK_749C879C4584665A FOREIGN KEY (product_id) REFERENCES "products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "orders_products" DROP CONSTRAINT FK_749C879C8D9F6D38');
        $this->addSql('ALTER TABLE "orders_products" DROP CONSTRAINT FK_749C879C4584665A');
        $this->addSql('DROP TABLE "orders_products"');
    }
}
