<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224144256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(20) NOT NULL, file_path VARCHAR(50) NOT NULL, type VARCHAR(20) NOT NULL, uploaded_at DATETIME NOT NULL, prospect_id INT DEFAULT NULL, INDEX IDX_D8698A76D182060A (prospect_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, cin VARCHAR(20) DEFAULT NULL, gender VARCHAR(10) DEFAULT NULL, dob DATE DEFAULT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(30) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, address LONGTEXT DEFAULT NULL, plan VARCHAR(30) DEFAULT NULL, start_date DATE DEFAULT NULL, beneficiaries INT DEFAULT NULL, conditions VARCHAR(50) DEFAULT NULL, premium DOUBLE PRECISION DEFAULT NULL, medical_notes LONGTEXT DEFAULT NULL, commercial_notes LONGTEXT DEFAULT NULL, documents JSON DEFAULT NULL, status VARCHAR(20) NOT NULL, rejection_note LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, claimed_by_id INT DEFAULT NULL, INDEX IDX_3D48E037F67E7A38 (claimed_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(25) NOT NULL, lastname VARCHAR(10) NOT NULL, phone INT NOT NULL, email VARCHAR(25) DEFAULT NULL, cin VARCHAR(10) DEFAULT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE prospect_user (prospect_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F7725CE2D182060A (prospect_id), INDEX IDX_F7725CE2A76ED395 (user_id), PRIMARY KEY (prospect_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id)');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037F67E7A38 FOREIGN KEY (claimed_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prospect_user ADD CONSTRAINT FK_F7725CE2D182060A FOREIGN KEY (prospect_id) REFERENCES prospect (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prospect_user ADD CONSTRAINT FK_F7725CE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76D182060A');
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E037F67E7A38');
        $this->addSql('ALTER TABLE prospect_user DROP FOREIGN KEY FK_F7725CE2D182060A');
        $this->addSql('ALTER TABLE prospect_user DROP FOREIGN KEY FK_F7725CE2A76ED395');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('DROP TABLE prospect_user');
    }
}
