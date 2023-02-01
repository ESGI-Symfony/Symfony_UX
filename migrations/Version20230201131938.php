<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201131938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental ADD rent_type VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE rental DROP name');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c6a7cf2329');
        $this->addSql('DROP INDEX idx_794381c6a7cf2329');
        $this->addSql('ALTER TABLE review RENAME COLUMN rental_id TO reservation_id');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_794381C6B83297E7 ON review (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6B83297E7');
        $this->addSql('DROP INDEX IDX_794381C6B83297E7');
        $this->addSql('ALTER TABLE review RENAME COLUMN reservation_id TO rental_id');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c6a7cf2329 FOREIGN KEY (rental_id) REFERENCES rental (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_794381c6a7cf2329 ON review (rental_id)');
        $this->addSql('ALTER TABLE rental ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rental DROP rent_type');
    }
}
