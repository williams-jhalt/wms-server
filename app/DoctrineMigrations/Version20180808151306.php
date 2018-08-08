<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180808151306 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_attribute (id INT AUTO_INCREMENT NOT NULL, detail_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_94DA5976D8D003BB (detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_attribute ADD CONSTRAINT FK_94DA5976D8D003BB FOREIGN KEY (detail_id) REFERENCES product_detail (id)');
        $this->addSql('ALTER TABLE product_detail DROP attributes');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_attribute');
        $this->addSql('ALTER TABLE product_detail ADD attributes LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\'');
    }
}
