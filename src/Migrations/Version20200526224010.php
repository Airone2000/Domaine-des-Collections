<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526224010 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE value ADD form_component_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE value ADD CONSTRAINT FK_1D77583488ECE49D FOREIGN KEY (form_component_id) REFERENCES form_component (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1D77583488ECE49D ON value (form_component_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE value DROP FOREIGN KEY FK_1D77583488ECE49D');
        $this->addSql('DROP INDEX IDX_1D77583488ECE49D ON value');
        $this->addSql('ALTER TABLE value DROP form_component_id');
    }
}
