<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726184556 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dsco_customer (id INT AUTO_INCREMENT NOT NULL, sender_company_id VARCHAR(255) NOT NULL, customer_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dsco_order_status (id INT AUTO_INCREMENT NOT NULL, dsco_key VARCHAR(255) NOT NULL, link_key VARCHAR(255) DEFAULT NULL, sender_company_id VARCHAR(255) NOT NULL, document_date DATETIME NOT NULL, order_date DATETIME NOT NULL, partner_po VARCHAR(255) NOT NULL, order_number VARCHAR(255) DEFAULT NULL, weborder_number VARCHAR(255) NOT NULL, customer_number VARCHAR(255) DEFAULT NULL, status_code INT NOT NULL, UNIQUE INDEX UNIQ_5B06C3309FBE4BB9 (dsco_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_detail ADD created_on DATETIME NOT NULL, ADD updated_on DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE dsco_customer');
        $this->addSql('DROP TABLE dsco_order_status');
        $this->addSql('ALTER TABLE product_detail DROP created_on, DROP updated_on');
    }
}
