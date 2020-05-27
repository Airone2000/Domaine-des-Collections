<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526223358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE thing (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) DEFAULT NULL, INDEX IDX_5B4C2C83514956FD (collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE value (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', thing_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', line_of_text_value VARCHAR(150) DEFAULT NULL, INDEX IDX_1D775834C36906A7 (thing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE thing ADD CONSTRAINT FK_5B4C2C83514956FD FOREIGN KEY (collection_id) REFERENCES collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE value ADD CONSTRAINT FK_1D775834C36906A7 FOREIGN KEY (thing_id) REFERENCES thing (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE value DROP FOREIGN KEY FK_1D775834C36906A7');
        $this->addSql('DROP TABLE thing');
        $this->addSql('DROP TABLE value');
    }
}
