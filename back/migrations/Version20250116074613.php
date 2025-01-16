<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116074613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id VARCHAR(36) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, state VARCHAR(100) DEFAULT NULL, postal_code VARCHAR(20) NOT NULL, country VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE customers (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, address_id VARCHAR(36) DEFAULT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(10) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62534E21A76ED395 ON customers (user_id)');
        $this->addSql('CREATE INDEX IDX_62534E21F5B7AF75 ON customers (address_id)');
        $this->addSql('CREATE TABLE email_campaigns (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, body TEXT DEFAULT NULL, scheduled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, mailjet_id INT NOT NULL, status VARCHAR(40) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EC78EB5BA76ED395 ON email_campaigns (user_id)');
        $this->addSql('CREATE TABLE products (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, stock_quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AA76ED395 ON products (user_id)');
        $this->addSql('CREATE TABLE sale (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, customer_id VARCHAR(36) NOT NULL, service_id VARCHAR(36) NOT NULL, sale_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, comment TEXT DEFAULT NULL, status VARCHAR(50) NOT NULL, payment_method VARCHAR(50) DEFAULT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subtotal DOUBLE PRECISION DEFAULT NULL, tax DOUBLE PRECISION DEFAULT NULL, transaction_reference VARCHAR(255) DEFAULT NULL, billing_address VARCHAR(255) DEFAULT NULL, invoice_number VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E54BC005A76ED395 ON sale (user_id)');
        $this->addSql('CREATE INDEX IDX_E54BC0059395C3F3 ON sale (customer_id)');
        $this->addSql('CREATE INDEX IDX_E54BC005ED5CA9E6 ON sale (service_id)');
        $this->addSql('CREATE TABLE service (id VARCHAR(36) NOT NULL, owner_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, hourly_rate DOUBLE PRECISION NOT NULL, duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD27E3C61F9 ON service (owner_id)');
        $this->addSql('CREATE TABLE users (id VARCHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(180) NOT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, image_url VARCHAR(255) DEFAULT NULL, contact_list_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE email_campaigns ADD CONSTRAINT FK_EC78EB5BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0059395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD27E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customers DROP CONSTRAINT FK_62534E21A76ED395');
        $this->addSql('ALTER TABLE customers DROP CONSTRAINT FK_62534E21F5B7AF75');
        $this->addSql('ALTER TABLE email_campaigns DROP CONSTRAINT FK_EC78EB5BA76ED395');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5AA76ED395');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC005A76ED395');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC0059395C3F3');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC005ED5CA9E6');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD27E3C61F9');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE email_campaigns');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE users');
    }
}
