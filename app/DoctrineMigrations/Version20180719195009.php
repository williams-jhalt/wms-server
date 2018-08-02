<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180719195009 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE manufacturer (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_policy (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, file_type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, detail_id INT DEFAULT NULL, manufacturer_id INT DEFAULT NULL, product_type_id INT DEFAULT NULL, map_policy_id INT DEFAULT NULL, item_number VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, wholesale_price NUMERIC(10, 2) DEFAULT NULL, release_date DATE NOT NULL, bin_location VARCHAR(255) DEFAULT NULL, quantity_on_hand INT DEFAULT NULL, quantity_committed INT DEFAULT NULL, created_on DATETIME NOT NULL, updated_on DATETIME NOT NULL, deleted TINYINT(1) NOT NULL, web_item TINYINT(1) NOT NULL, warehouse VARCHAR(255) NOT NULL, unit_of_measure VARCHAR(255) NOT NULL, barcode VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04ADD8D003BB (detail_id), INDEX IDX_D34A04ADA23B42D (manufacturer_id), INDEX IDX_D34A04AD14959723 (product_type_id), INDEX IDX_D34A04AD8841A49B (map_policy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_attachment (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, url VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, file_type VARCHAR(255) NOT NULL, explicit TINYINT(1) NOT NULL, INDEX IDX_EA3886904584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_detail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, html_description LONGTEXT DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, package_height NUMERIC(10, 3) DEFAULT NULL, package_length NUMERIC(10, 3) DEFAULT NULL, package_width NUMERIC(10, 3) DEFAULT NULL, package_weight NUMERIC(10, 3) DEFAULT NULL, dim_unit VARCHAR(255) DEFAULT NULL, weight_unit VARCHAR(255) DEFAULT NULL, msrp NUMERIC(10, 2) DEFAULT NULL, map_price NUMERIC(10, 2) DEFAULT NULL, attributes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD8D003BB FOREIGN KEY (detail_id) REFERENCES product_detail (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD8841A49B FOREIGN KEY (map_policy_id) REFERENCES map_policy (id)');
        $this->addSql('ALTER TABLE product_attachment ADD CONSTRAINT FK_EA3886904584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA23B42D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD8841A49B');
        $this->addSql('ALTER TABLE product_attachment DROP FOREIGN KEY FK_EA3886904584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD8D003BB');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD14959723');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE map_policy');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_attachment');
        $this->addSql('DROP TABLE product_detail');
        $this->addSql('DROP TABLE product_type');
    }
}
