<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518095328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE property_note (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, created DATE NOT NULL, comement LONGTEXT NOT NULL, INDEX IDX_D0A5452E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_note ADD CONSTRAINT FK_D0A5452E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE property ADD property_note_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE7F20422D FOREIGN KEY (property_note_id) REFERENCES property_note (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE7F20422D ON property (property_note_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE7F20422D');
        $this->addSql('DROP TABLE property_note');
        $this->addSql('DROP INDEX IDX_8BF21CDE7F20422D ON property');
        $this->addSql('ALTER TABLE property DROP property_note_id');
    }
}
