<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200418223826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE owner ADD agent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE owner ADD CONSTRAINT FK_CF60E67C3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('CREATE INDEX IDX_CF60E67C3414710B ON owner (agent_id)');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D19EB6921');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D7E3C61F9');
        $this->addSql('DROP INDEX IDX_268B9C9D7E3C61F9 ON agent');
        $this->addSql('DROP INDEX IDX_268B9C9D19EB6921 ON agent');
        $this->addSql('ALTER TABLE agent DROP client_id, DROP owner_id');
        $this->addSql('ALTER TABLE client ADD agent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404553414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('CREATE INDEX IDX_C74404553414710B ON client (agent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agent ADD client_id INT DEFAULT NULL, ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D7E3C61F9 ON agent (owner_id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D19EB6921 ON agent (client_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404553414710B');
        $this->addSql('DROP INDEX IDX_C74404553414710B ON client');
        $this->addSql('ALTER TABLE client DROP agent_id');
        $this->addSql('ALTER TABLE owner DROP FOREIGN KEY FK_CF60E67C3414710B');
        $this->addSql('DROP INDEX IDX_CF60E67C3414710B ON owner');
        $this->addSql('ALTER TABLE owner DROP agent_id');
    }
}
