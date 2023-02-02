<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202115000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rental (id INT NOT NULL, owner_id INT NOT NULL, description TEXT NOT NULL, price DOUBLE PRECISION NOT NULL, max_capacity INT NOT NULL, room_count INT NOT NULL, bathroom_count INT NOT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, rent_type VARCHAR(50) NOT NULL, system VARCHAR(255) NOT NULL, celestial_object VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1619C27D7E3C61F9 ON rental (owner_id)');
        $this->addSql('CREATE TABLE rental_rental_option (rental_id INT NOT NULL, rental_option_id INT NOT NULL, PRIMARY KEY(rental_id, rental_option_id))');
        $this->addSql('CREATE INDEX IDX_32244FF5A7CF2329 ON rental_rental_option (rental_id)');
        $this->addSql('CREATE INDEX IDX_32244FF518241A60 ON rental_rental_option (rental_option_id)');
        $this->addSql('CREATE TABLE rental_transport (rental_id INT NOT NULL, transport_id INT NOT NULL, PRIMARY KEY(rental_id, transport_id))');
        $this->addSql('CREATE INDEX IDX_D94B2621A7CF2329 ON rental_transport (rental_id)');
        $this->addSql('CREATE INDEX IDX_D94B26219909C13F ON rental_transport (transport_id)');
        $this->addSql('CREATE TABLE rental_option (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE report (id INT NOT NULL, rental_id INT NOT NULL, author_id INT NOT NULL, comment TEXT NOT NULL, type VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C42F7784A7CF2329 ON report (rental_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784F675F31B ON report (author_id)');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, rental_id INT NOT NULL, buyer_id INT NOT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, payment_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955A7CF2329 ON reservation (rental_id)');
        $this->addSql('CREATE INDEX IDX_42C849556C755722 ON reservation (buyer_id)');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, author_id INT NOT NULL, reservation_id INT NOT NULL, comment TEXT NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
        $this->addSql('CREATE INDEX IDX_794381C6B83297E7 ON review (reservation_id)');
        $this->addSql('CREATE TABLE transport (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_verified BOOLEAN DEFAULT false NOT NULL, firstname VARCHAR(150) DEFAULT NULL, lastname VARCHAR(150) DEFAULT NULL, lessor_number INT DEFAULT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_lessor_request (id INT NOT NULL, lessor_id INT NOT NULL, motivation TEXT NOT NULL, status VARCHAR(10) NOT NULL, refusing_reason TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EF9AB304D737E9B1 ON user_lessor_request (lessor_id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_rental_option ADD CONSTRAINT FK_32244FF5A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_rental_option ADD CONSTRAINT FK_32244FF518241A60 FOREIGN KEY (rental_option_id) REFERENCES rental_option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_transport ADD CONSTRAINT FK_D94B2621A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_transport ADD CONSTRAINT FK_D94B26219909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_lessor_request ADD CONSTRAINT FK_EF9AB304D737E9B1 FOREIGN KEY (lessor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rental DROP CONSTRAINT FK_1619C27D7E3C61F9');
        $this->addSql('ALTER TABLE rental_rental_option DROP CONSTRAINT FK_32244FF5A7CF2329');
        $this->addSql('ALTER TABLE rental_rental_option DROP CONSTRAINT FK_32244FF518241A60');
        $this->addSql('ALTER TABLE rental_transport DROP CONSTRAINT FK_D94B2621A7CF2329');
        $this->addSql('ALTER TABLE rental_transport DROP CONSTRAINT FK_D94B26219909C13F');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784A7CF2329');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784F675F31B');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A7CF2329');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849556C755722');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6B83297E7');
        $this->addSql('ALTER TABLE user_lessor_request DROP CONSTRAINT FK_EF9AB304D737E9B1');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE rental_rental_option');
        $this->addSql('DROP TABLE rental_transport');
        $this->addSql('DROP TABLE rental_option');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE transport');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_lessor_request');
    }
}
