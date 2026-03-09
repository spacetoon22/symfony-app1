<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227134708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting CHANGE reg_number reg_number VARCHAR(255) DEFAULT NULL, CHANGE contact_phone contact_phone VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE address address LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting CHANGE reg_number reg_number VARCHAR(100) DEFAULT NULL, CHANGE contact_phone contact_phone VARCHAR(50) DEFAULT NULL, CHANGE city city VARCHAR(100) DEFAULT NULL, CHANGE address address TEXT DEFAULT NULL');
    }
}
