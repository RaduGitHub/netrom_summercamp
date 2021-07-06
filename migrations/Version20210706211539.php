<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210706211539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity');
        $this->addSql('ALTER TABLE license_plate CHANGE user_id user_id INT NOT NULL');
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (blockee VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, blocker VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, status INT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE license_plate CHANGE user_id user_id INT DEFAULT NULL');
    }
}
