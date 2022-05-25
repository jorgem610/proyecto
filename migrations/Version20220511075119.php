<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511075119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08700047D2');
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08ED07566B');
        $this->addSql('DROP INDEX IDX_4B708A08ED07566B ON linea_ticket');
        $this->addSql('ALTER TABLE linea_ticket DROP productos_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE linea_ticket ADD productos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08ED07566B FOREIGN KEY (productos_id) REFERENCES productos (id)');
        $this->addSql('CREATE INDEX IDX_4B708A08ED07566B ON linea_ticket (productos_id)');
    }
}
