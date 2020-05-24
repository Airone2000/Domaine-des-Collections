<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523234152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_email_address (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(255) DEFAULT NULL, verified TINYINT(1) DEFAULT \'0\' NOT NULL, verified_at DATETIME DEFAULT NULL, verification_token CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', news_letter_accepted TINYINT(1) DEFAULT \'0\' NOT NULL, history_of_verified_email_addresses LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', history_of_verified_email_addresses_kept TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_5E00AA1EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_email_address ADD CONSTRAINT FK_5E00AA1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_email_address DROP FOREIGN KEY FK_5E00AA1EA76ED395');
        $this->addSql('DROP TABLE user_email_address');
        $this->addSql('DROP TABLE user');
    }
}
