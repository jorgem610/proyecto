<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427231404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imagenes ADD CONSTRAINT FK_376A60017645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08ED07566B FOREIGN KEY (productos_id) REFERENCES productos (id)');
        $this->addSql('ALTER TABLE linea_ticket ADD CONSTRAINT FK_4B708A08700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE productos ADD CONSTRAINT FK_767490E63397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imagenes DROP FOREIGN KEY FK_376A60017645698E');
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08ED07566B');
        $this->addSql('ALTER TABLE linea_ticket DROP FOREIGN KEY FK_4B708A08700047D2');
        $this->addSql('ALTER TABLE productos DROP FOREIGN KEY FK_767490E63397707A');
    }
}
