<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430162607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD captured_data_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F9DACD41E FOREIGN KEY (captured_data_id) REFERENCES captured_data (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F9DACD41E ON image (captured_data_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F9DACD41E');
        $this->addSql('DROP INDEX IDX_C53D045F9DACD41E ON image');
        $this->addSql('ALTER TABLE image DROP captured_data_id');
    }
}
