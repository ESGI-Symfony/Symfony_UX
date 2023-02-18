<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212113017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c6f675f31b');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c6b83297e7');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE reservation ADD review_mark INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD review_comment TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, author_id INT NOT NULL, reservation_id INT NOT NULL, comment TEXT NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_794381c6b83297e7 ON review (reservation_id)');
        $this->addSql('CREATE INDEX idx_794381c6f675f31b ON review (author_id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c6f675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c6b83297e7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation DROP review_mark');
        $this->addSql('ALTER TABLE reservation DROP review_comment');
    }
}
