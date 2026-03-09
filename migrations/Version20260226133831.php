<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226133831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier ADD assigned_to_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D48E037F4BD7827 ON dossier (assigned_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E037F4BD7827');
        $this->addSql('DROP INDEX IDX_3D48E037F4BD7827 ON dossier');
        $this->addSql('ALTER TABLE dossier DROP assigned_to_id');
    }
}
