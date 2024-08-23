<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823115733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wash_list (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cours_id INT DEFAULT NULL, INDEX IDX_88622208A76ED395 (user_id), INDEX IDX_886222087ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wash_list ADD CONSTRAINT FK_88622208A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wash_list ADD CONSTRAINT FK_886222087ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wash_list DROP FOREIGN KEY FK_88622208A76ED395');
        $this->addSql('ALTER TABLE wash_list DROP FOREIGN KEY FK_886222087ECF78B0');
        $this->addSql('DROP TABLE wash_list');
    }
}
