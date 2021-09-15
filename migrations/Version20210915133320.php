<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915133320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catastrophe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catastrophe_item (catastrophe_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_833415E850560F60 (catastrophe_id), INDEX IDX_833415E8126F525E (item_id), PRIMARY KEY(catastrophe_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventories (id INT AUTO_INCREMENT NOT NULL, company_name_id INT NOT NULL, content LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_936C863D51458601 (company_name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, catastrophe_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, date DATE NOT NULL, activate TINYINT(1) NOT NULL, besoins LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_DD5A5B7D50560F60 (catastrophe_id), INDEX IDX_DD5A5B7D9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mobile_phone VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, logo_name VARCHAR(255) NOT NULL, activated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64912469DE2 (category_id), INDEX IDX_8D93D6499F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catastrophe_item ADD CONSTRAINT FK_833415E850560F60 FOREIGN KEY (catastrophe_id) REFERENCES catastrophe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE catastrophe_item ADD CONSTRAINT FK_833415E8126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventories ADD CONSTRAINT FK_936C863D51458601 FOREIGN KEY (company_name_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7D50560F60 FOREIGN KEY (catastrophe_id) REFERENCES catastrophe (id)');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7D9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catastrophe_item DROP FOREIGN KEY FK_833415E850560F60');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7D50560F60');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64912469DE2');
        $this->addSql('ALTER TABLE catastrophe_item DROP FOREIGN KEY FK_833415E8126F525E');
        $this->addSql('ALTER TABLE inventories DROP FOREIGN KEY FK_936C863D51458601');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7D9F2C3FAB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499F2C3FAB');
        $this->addSql('DROP TABLE catastrophe');
        $this->addSql('DROP TABLE catastrophe_item');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE inventories');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zone');
    }
}
