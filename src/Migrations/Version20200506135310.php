<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200506135310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE street DROP FOREIGN KEY FK_F0EED3D8549213EC');
        $this->addSql('DROP INDEX IDX_F0EED3D8549213EC ON street');
        $this->addSql('ALTER TABLE street DROP property_id');
        $this->addSql('ALTER TABLE property ADD street_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE87CF8EB FOREIGN KEY (street_id) REFERENCES street (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE87CF8EB ON property (street_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE87CF8EB');
        $this->addSql('DROP INDEX IDX_8BF21CDE87CF8EB ON property');
        $this->addSql('ALTER TABLE property DROP street_id');
        $this->addSql('ALTER TABLE street ADD property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE street ADD CONSTRAINT FK_F0EED3D8549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_F0EED3D8549213EC ON street (property_id)');
    }
}
