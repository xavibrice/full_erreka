<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200421225520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agent ADD agency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DCDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9DCDEADB2A ON agent (agency_id)');
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E63414710B');
        $this->addSql('DROP INDEX IDX_70C0C6E63414710B ON agency');
        $this->addSql('ALTER TABLE agency DROP agent_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agency ADD agent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E63414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('CREATE INDEX IDX_70C0C6E63414710B ON agency (agent_id)');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DCDEADB2A');
        $this->addSql('DROP INDEX IDX_268B9C9DCDEADB2A ON agent');
        $this->addSql('ALTER TABLE agent DROP agency_id');
    }
}
