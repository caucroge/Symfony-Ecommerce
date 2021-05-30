<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210527150747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE ligne_panier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE ligne_panier (id INT NOT NULL, product_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, quantity INT NOT NULL, total INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_21691B44584665A ON ligne_panier (product_id)');
        $this->addSql('CREATE INDEX IDX_21691B4A76ED395 ON ligne_panier (user_id)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B44584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT fk_64c19c17e3c61f9');
        $this->addSql('DROP INDEX idx_64c19c17e3c61f9');
        $this->addSql('ALTER TABLE category DROP owner_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE ligne_panier_id_seq CASCADE');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('ALTER TABLE category ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT fk_64c19c17e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_64c19c17e3c61f9 ON category (owner_id)');
    }
}
