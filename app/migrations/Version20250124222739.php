<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124222739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE baskets (id SERIAL NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_basket_user_product ON baskets (user_id, product_id)');
        $this->addSql('CREATE INDEX IDX_4E004AAC9D86650F ON baskets (user_id)');
        $this->addSql('CREATE INDEX IDX_4E004AACDE18E50B ON baskets (product_id)');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_4E004AAC9D86650F FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE baskets ADD CONSTRAINT FK_4E004AACDE18E50B FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX unique_basket_user_product');
        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT FK_4E004AAC9D86650F');
        $this->addSql('ALTER TABLE baskets DROP CONSTRAINT FK_4E004AACDE18E50B');
        $this->addSql('DROP TABLE baskets');
    }
}
