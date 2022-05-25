<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507112103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08700047D2');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08700047D2');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
    }
}
