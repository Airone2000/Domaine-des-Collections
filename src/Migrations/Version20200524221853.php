<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200524221853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE form_component_widget (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', form_component_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', type VARCHAR(15) DEFAULT \'none\' NOT NULL, options JSON NOT NULL, UNIQUE INDEX UNIQ_A65F198788ECE49D (form_component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(200) DEFAULT NULL, private TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', form_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', valid TINYINT(1) NOT NULL, INDEX IDX_38FE1E5B5FF69B7D (form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_member (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', role VARCHAR(10) NOT NULL, INDEX IDX_82C1028EA76ED395 (user_id), INDEX IDX_82C1028E514956FD (collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_component_position (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', form_component_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', fallback INT NOT NULL, smartphone INT DEFAULT NULL, tablet INT DEFAULT NULL, desktop INT DEFAULT NULL, UNIQUE INDEX UNIQ_2DB2996388ECE49D (form_component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_email_address (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(255) DEFAULT NULL, verified TINYINT(1) DEFAULT \'0\' NOT NULL, verified_at DATETIME DEFAULT NULL, verification_token CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', news_letter_accepted TINYINT(1) DEFAULT \'0\' NOT NULL, history_of_verified_email_addresses LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', history_of_verified_email_addresses_kept TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_5E00AA1EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_gender (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', value VARCHAR(1) DEFAULT NULL, visible TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A215117FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_birthdate (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', value DATE DEFAULT NULL, visible TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9204D1C7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_component_size (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', form_component_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', fallback NUMERIC(5, 2) NOT NULL, smartphone NUMERIC(5, 2) DEFAULT NULL, tablet NUMERIC(5, 2) DEFAULT NULL, desktop NUMERIC(5, 2) DEFAULT NULL, UNIQUE INDEX UNIQ_FD25682688ECE49D (form_component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', in_edition TINYINT(1) NOT NULL, valid TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5288FD4F514956FD (collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE form_component_widget ADD CONSTRAINT FK_A65F198788ECE49D FOREIGN KEY (form_component_id) REFERENCES form_component (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_component ADD CONSTRAINT FK_38FE1E5B5FF69B7D FOREIGN KEY (form_id) REFERENCES form (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_member ADD CONSTRAINT FK_82C1028EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_member ADD CONSTRAINT FK_82C1028E514956FD FOREIGN KEY (collection_id) REFERENCES collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_component_position ADD CONSTRAINT FK_2DB2996388ECE49D FOREIGN KEY (form_component_id) REFERENCES form_component (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_email_address ADD CONSTRAINT FK_5E00AA1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_gender ADD CONSTRAINT FK_A215117FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_birthdate ADD CONSTRAINT FK_9204D1C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_component_size ADD CONSTRAINT FK_FD25682688ECE49D FOREIGN KEY (form_component_id) REFERENCES form_component (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4F514956FD FOREIGN KEY (collection_id) REFERENCES collection (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE collection_member DROP FOREIGN KEY FK_82C1028E514956FD');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4F514956FD');
        $this->addSql('ALTER TABLE form_component_widget DROP FOREIGN KEY FK_A65F198788ECE49D');
        $this->addSql('ALTER TABLE form_component_position DROP FOREIGN KEY FK_2DB2996388ECE49D');
        $this->addSql('ALTER TABLE form_component_size DROP FOREIGN KEY FK_FD25682688ECE49D');
        $this->addSql('ALTER TABLE collection_member DROP FOREIGN KEY FK_82C1028EA76ED395');
        $this->addSql('ALTER TABLE user_email_address DROP FOREIGN KEY FK_5E00AA1EA76ED395');
        $this->addSql('ALTER TABLE user_gender DROP FOREIGN KEY FK_A215117FA76ED395');
        $this->addSql('ALTER TABLE user_birthdate DROP FOREIGN KEY FK_9204D1C7A76ED395');
        $this->addSql('ALTER TABLE form_component DROP FOREIGN KEY FK_38FE1E5B5FF69B7D');
        $this->addSql('DROP TABLE form_component_widget');
        $this->addSql('DROP TABLE collection');
        $this->addSql('DROP TABLE form_component');
        $this->addSql('DROP TABLE collection_member');
        $this->addSql('DROP TABLE form_component_position');
        $this->addSql('DROP TABLE user_email_address');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_gender');
        $this->addSql('DROP TABLE user_birthdate');
        $this->addSql('DROP TABLE form_component_size');
        $this->addSql('DROP TABLE form');
    }
}
