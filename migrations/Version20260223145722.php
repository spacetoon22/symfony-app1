<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223145722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(25) NOT NULL, lastname VARCHAR(10) NOT NULL, phone INT NOT NULL, email VARCHAR(25) DEFAULT NULL, cin VARCHAR(10) DEFAULT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE prospect_user (prospect_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F7725CE2D182060A (prospect_id), INDEX IDX_F7725CE2A76ED395 (user_id), PRIMARY KEY (prospect_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE prospect_user ADD CONSTRAINT FK_F7725CE2D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prospect_user ADD CONSTRAINT FK_F7725CE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospect_user DROP FOREIGN KEY FK_F7725CE2D182060A');
        $this->addSql('ALTER TABLE prospect_user DROP FOREIGN KEY FK_F7725CE2A76ED395');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('DROP TABLE prospect_user');
    }
}
