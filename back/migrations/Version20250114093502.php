<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114093502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, customer_id VARCHAR(36) NOT NULL, service_id VARCHAR(36) NOT NULL, sale_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, comment TEXT DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, payment_method VARCHAR(50) DEFAULT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, subtotal DOUBLE PRECISION DEFAULT NULL, tax DOUBLE PRECISION DEFAULT NULL, transaction_reference VARCHAR(255) DEFAULT NULL, billing_address VARCHAR(255) DEFAULT NULL, invoice_number VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E54BC005A76ED395 ON sale (user_id)');
        $this->addSql('CREATE INDEX IDX_E54BC0059395C3F3 ON sale (customer_id)');
        $this->addSql('CREATE INDEX IDX_E54BC005ED5CA9E6 ON sale (service_id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0059395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sales DROP CONSTRAINT fk_6b817044a76ed395');
        $this->addSql('ALTER TABLE sales DROP CONSTRAINT fk_6b8170449395c3f3');
        $this->addSql('ALTER TABLE sales DROP CONSTRAINT fk_6b8170444584665a');
        $this->addSql('DROP TABLE sales');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE sales (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, customer_id VARCHAR(36) DEFAULT NULL, product_id VARCHAR(36) DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, sale_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6b8170444584665a ON sales (product_id)');
        $this->addSql('CREATE INDEX idx_6b8170449395c3f3 ON sales (customer_id)');
        $this->addSql('CREATE INDEX idx_6b817044a76ed395 ON sales (user_id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT fk_6b817044a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT fk_6b8170449395c3f3 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT fk_6b8170444584665a FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC005A76ED395');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC0059395C3F3');
        $this->addSql('ALTER TABLE sale DROP CONSTRAINT FK_E54BC005ED5CA9E6');
        $this->addSql('DROP TABLE sale');
    }
}
