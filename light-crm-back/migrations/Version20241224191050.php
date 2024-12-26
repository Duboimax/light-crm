<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241224191050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customers (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(10) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62534E21A76ED395 ON customers (user_id)');
        $this->addSql('CREATE TABLE email_campaigns (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, body TEXT DEFAULT NULL, scheduled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC78EB5BA76ED395 ON email_campaigns (user_id)');
        $this->addSql('CREATE TABLE products (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AA76ED395 ON products (user_id)');
        $this->addSql('CREATE TABLE sales (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, customer_id VARCHAR(36) DEFAULT NULL, product_id VARCHAR(36) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, sale_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B817044A76ED395 ON sales (user_id)');
        $this->addSql('CREATE INDEX IDX_6B8170449395C3F3 ON sales (customer_id)');
        $this->addSql('CREATE INDEX IDX_6B8170444584665A ON sales (product_id)');
        $this->addSql('CREATE TABLE users (id VARCHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE email_campaigns ADD CONSTRAINT FK_EC78EB5BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B817044A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B8170449395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B8170444584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customers DROP CONSTRAINT FK_62534E21A76ED395');
        $this->addSql('ALTER TABLE email_campaigns DROP CONSTRAINT FK_EC78EB5BA76ED395');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5AA76ED395');
        $this->addSql('ALTER TABLE sales DROP CONSTRAINT FK_6B817044A76ED395');
        $this->addSql('ALTER TABLE sales DROP CONSTRAINT FK_6B8170449395C3F3');
        $this->addSql('ALTER TABLE sales DROP CONSTRAINT FK_6B8170444584665A');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE email_campaigns');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE sales');
        $this->addSql('DROP TABLE users');
    }
}
