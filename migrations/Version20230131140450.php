<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131140450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rental_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rental_option_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transport_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_lessor_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, system VARCHAR(255) NOT NULL, celestial_object VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE address_transport (address_id INT NOT NULL, transport_id INT NOT NULL, PRIMARY KEY(address_id, transport_id))');
        $this->addSql('CREATE INDEX IDX_FCEA1D66F5B7AF75 ON address_transport (address_id)');
        $this->addSql('CREATE INDEX IDX_FCEA1D669909C13F ON address_transport (transport_id)');
        $this->addSql('CREATE TABLE rental (id INT NOT NULL, address_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, price DOUBLE PRECISION NOT NULL, max_capacity INT NOT NULL, room_count INT NOT NULL, bathroom_count INT NOT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1619C27DF5B7AF75 ON rental (address_id)');
        $this->addSql('CREATE INDEX IDX_1619C27D7E3C61F9 ON rental (owner_id)');
        $this->addSql('CREATE TABLE rental_rental_option (rental_id INT NOT NULL, rental_option_id INT NOT NULL, PRIMARY KEY(rental_id, rental_option_id))');
        $this->addSql('CREATE INDEX IDX_32244FF5A7CF2329 ON rental_rental_option (rental_id)');
        $this->addSql('CREATE INDEX IDX_32244FF518241A60 ON rental_rental_option (rental_option_id)');
        $this->addSql('CREATE TABLE rental_option (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE report (id INT NOT NULL, rental_id INT NOT NULL, comment TEXT NOT NULL, type VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C42F7784A7CF2329 ON report (rental_id)');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, rental_id INT NOT NULL, buyer_id INT NOT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, payment_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955A7CF2329 ON reservation (rental_id)');
        $this->addSql('CREATE INDEX IDX_42C849556C755722 ON reservation (buyer_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, rental_id INT NOT NULL, author_id INT NOT NULL, comment TEXT NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6A7CF2329 ON review (rental_id)');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
        $this->addSql('CREATE TABLE transport (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_lessor_request (id INT NOT NULL, lessor_id INT NOT NULL, motivation TEXT NOT NULL, status VARCHAR(10) NOT NULL, refusing_reason TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EF9AB304D737E9B1 ON user_lessor_request (lessor_id)');
        $this->addSql('ALTER TABLE address_transport ADD CONSTRAINT FK_FCEA1D66F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE address_transport ADD CONSTRAINT FK_FCEA1D669909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_rental_option ADD CONSTRAINT FK_32244FF5A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_rental_option ADD CONSTRAINT FK_32244FF518241A60 FOREIGN KEY (rental_option_id) REFERENCES rental_option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_lessor_request ADD CONSTRAINT FK_EF9AB304D737E9B1 FOREIGN KEY (lessor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD firstname VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD lastname VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD lessor_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN pseudo TO nickname');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rental_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rental_option_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE report_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transport_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_lessor_request_id_seq CASCADE');
        $this->addSql('ALTER TABLE address_transport DROP CONSTRAINT FK_FCEA1D66F5B7AF75');
        $this->addSql('ALTER TABLE address_transport DROP CONSTRAINT FK_FCEA1D669909C13F');
        $this->addSql('ALTER TABLE rental DROP CONSTRAINT FK_1619C27DF5B7AF75');
        $this->addSql('ALTER TABLE rental DROP CONSTRAINT FK_1619C27D7E3C61F9');
        $this->addSql('ALTER TABLE rental_rental_option DROP CONSTRAINT FK_32244FF5A7CF2329');
        $this->addSql('ALTER TABLE rental_rental_option DROP CONSTRAINT FK_32244FF518241A60');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784A7CF2329');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A7CF2329');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849556C755722');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6A7CF2329');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE user_lessor_request DROP CONSTRAINT FK_EF9AB304D737E9B1');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE address_transport');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE rental_rental_option');
        $this->addSql('DROP TABLE rental_option');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE transport');
        $this->addSql('DROP TABLE user_lessor_request');
        $this->addSql('ALTER TABLE "user" DROP firstname');
        $this->addSql('ALTER TABLE "user" DROP lastname');
        $this->addSql('ALTER TABLE "user" DROP lessor_number');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN nickname TO pseudo');
    }
}
