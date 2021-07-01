<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630084111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE license_plate (id INT AUTO_INCREMENT NOT NULL, license_plate_id INT NOT NULL, user_id_id INT DEFAULT NULL, INDEX IDX_F5AA79D0233678BC (license_plate_id), UNIQUE INDEX UNIQ_F5AA79D09D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE license_plate ADD CONSTRAINT FK_F5AA79D0233678BC FOREIGN KEY (license_plate_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE license_plate ADD CONSTRAINT FK_F5AA79D09D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE license_plate');
    }
}
