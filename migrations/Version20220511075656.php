<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511075656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imagenes DROP FOREIGN KEY FK_376A60017645698E');
        $this->addSql('ALTER TABLE imagenes CHANGE producto_id producto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE imagenes ADD CONSTRAINT FK_376A60017645698E FOREIGN KEY (producto_id) REFERENCES productos (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imagenes DROP FOREIGN KEY FK_376A60017645698E');
        $this->addSql('ALTER TABLE imagenes CHANGE producto_id producto_id INT NOT NULL');
        $this->addSql('ALTER TABLE imagenes ADD CONSTRAINT FK_376A60017645698E FOREIGN KEY (producto_id) REFERENCES productos (id)');
    }
}
