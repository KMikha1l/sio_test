<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201121349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discount_coupon (name VARCHAR(255) NOT NULL, type ENUM(\'percent\', \'subtraction\'), amount NUMERIC(10, 2) NOT NULL, currency VARCHAR(255) DEFAULT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price_amount NUMERIC(10, 2) NOT NULL, price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, coupon_name VARCHAR(255) DEFAULT NULL, tax_number VARCHAR(255) NOT NULL, total_price_amount NUMERIC(10, 2) NOT NULL, total_price_currency VARCHAR(3) NOT NULL, INDEX IDX_6117D13B1E1DA204 (coupon_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B1E1DA204 FOREIGN KEY (coupon_name) REFERENCES discount_coupon (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B1E1DA204');
        $this->addSql('DROP TABLE discount_coupon');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE purchase');
    }
}
