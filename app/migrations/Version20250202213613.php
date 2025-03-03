<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202213613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "orders" (id SERIAL NOT NULL, user_id INT NOT NULL, phone VARCHAR(255) NOT NULL, shipment_method_id INT NOT NULL, status_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEEA76ED395 ON "orders" (user_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE239B3F56 ON "orders" (shipment_method_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE6BF700BD ON "orders" (status_id)');
        $this->addSql('ALTER TABLE "orders" ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "orders" ADD CONSTRAINT FK_E52FFDEE239B3F56 FOREIGN KEY (shipment_method_id) REFERENCES "orders_shipments_methods" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "orders" ADD CONSTRAINT FK_E52FFDEE6BF700BD FOREIGN KEY (status_id) REFERENCES "orders_statuses" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_4e004aac9d86650f RENAME TO IDX_4E004AACA76ED395');
        $this->addSql('ALTER INDEX idx_4e004aacde18e50b RENAME TO IDX_4E004AAC4584665A');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "orders" DROP CONSTRAINT FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE "orders" DROP CONSTRAINT FK_E52FFDEE239B3F56');
        $this->addSql('ALTER TABLE "orders" DROP CONSTRAINT FK_E52FFDEE6BF700BD');
        $this->addSql('DROP TABLE "orders"');
        $this->addSql('ALTER INDEX idx_4e004aac4584665a RENAME TO idx_4e004aacde18e50b');
        $this->addSql('ALTER INDEX idx_4e004aaca76ed395 RENAME TO idx_4e004aac9d86650f');
    }
}
