<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705033123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE license_plate DROP FOREIGN KEY FK_F5AA79D09D86650F');
        $this->addSql('DROP INDEX IDX_F5AA79D09D86650F ON license_plate');
        $this->addSql('ALTER TABLE license_plate ADD user_id INT NOT NULL, DROP user_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE license_plate ADD user_id_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE license_plate ADD CONSTRAINT FK_F5AA79D09D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5AA79D09D86650F ON license_plate (user_id_id)');
    }
}
