<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511075236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_ticket ADD producto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A087645698E FOREIGN KEY (producto_id) REFERENCES productos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4B708A087645698E ON linea_ticket (producto_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A087645698E');
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08700047D2');
        $this->addSql('DROP INDEX IDX_4B708A087645698E ON linea_ticket');
        $this->addSql('ALTER TABLE linea_ticket DROP producto_id');
    }
}
