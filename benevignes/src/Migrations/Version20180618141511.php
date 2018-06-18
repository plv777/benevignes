<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180618141511 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD email VARCHAR(320) NOT NULL, ADD winearea VARCHAR(25) DEFAULT NULL, ADD password VARCHAR(60) NOT NULL, ADD phone VARCHAR(14) NOT NULL, ADD address VARCHAR(350) NOT NULL, ADD zipcode VARCHAR(5) NOT NULL, ADD city VARCHAR(100) NOT NULL, ADD offer INT DEFAULT NULL, ADD active TINYINT(1) NOT NULL, ADD type INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP email, DROP winearea, DROP password, DROP phone, DROP address, DROP zipcode, DROP city, DROP offer, DROP active, DROP type');
    }
}
