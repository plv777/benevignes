<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180618153912 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer CHANGE dateend enddate DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone phone CHAR(14) NOT NULL, CHANGE zipcode zipcode CHAR(5) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer CHANGE enddate dateend DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone phone VARCHAR(14) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE zipcode zipcode VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
