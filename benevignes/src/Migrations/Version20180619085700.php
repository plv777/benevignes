<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180619085700 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, status INT NOT NULL, description VARCHAR(1000) NOT NULL, INDEX IDX_29D6873E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request_offer (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, offer_id INT NOT NULL, date DATETIME NOT NULL, message VARCHAR(500) NOT NULL, INDEX IDX_74C13B1BF675F31B (author_id), INDEX IDX_74C13B1B53C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(320) NOT NULL, password VARCHAR(60) NOT NULL, active TINYINT(1) NOT NULL, type INT NOT NULL, active_token VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_offer ADD CONSTRAINT FK_74C13B1BF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE request_offer ADD CONSTRAINT FK_74C13B1B53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE request_offer DROP FOREIGN KEY FK_74C13B1B53C674EE');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E7E3C61F9');
        $this->addSql('ALTER TABLE request_offer DROP FOREIGN KEY FK_74C13B1BF675F31B');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE request_offer');
        $this->addSql('DROP TABLE user');
    }
}
