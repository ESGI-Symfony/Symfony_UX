<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202103809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental DROP CONSTRAINT fk_1619c27df5b7af75');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('CREATE TABLE rental_transport (rental_id INT NOT NULL, transport_id INT NOT NULL, PRIMARY KEY(rental_id, transport_id))');
        $this->addSql('CREATE INDEX IDX_D94B2621A7CF2329 ON rental_transport (rental_id)');
        $this->addSql('CREATE INDEX IDX_D94B26219909C13F ON rental_transport (transport_id)');
        $this->addSql('ALTER TABLE rental_transport ADD CONSTRAINT FK_D94B2621A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_transport ADD CONSTRAINT FK_D94B26219909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE address_transport DROP CONSTRAINT fk_fcea1d66f5b7af75');
        $this->addSql('ALTER TABLE address_transport DROP CONSTRAINT fk_fcea1d669909c13f');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE address_transport');
        $this->addSql('DROP INDEX idx_1619c27df5b7af75');
        $this->addSql('ALTER TABLE rental ADD system VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rental ADD celestial_object VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rental ADD longitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE rental ADD latitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE rental DROP address_id');
        $this->addSql('ALTER TABLE report ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C42F7784F675F31B ON report (author_id)');
        $this->addSql('ALTER TABLE "user" ADD uuid UUID NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, system VARCHAR(255) NOT NULL, celestial_object VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE address_transport (address_id INT NOT NULL, transport_id INT NOT NULL, PRIMARY KEY(address_id, transport_id))');
        $this->addSql('CREATE INDEX idx_fcea1d669909c13f ON address_transport (transport_id)');
        $this->addSql('CREATE INDEX idx_fcea1d66f5b7af75 ON address_transport (address_id)');
        $this->addSql('ALTER TABLE address_transport ADD CONSTRAINT fk_fcea1d66f5b7af75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE address_transport ADD CONSTRAINT fk_fcea1d669909c13f FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rental_transport DROP CONSTRAINT FK_D94B2621A7CF2329');
        $this->addSql('ALTER TABLE rental_transport DROP CONSTRAINT FK_D94B26219909C13F');
        $this->addSql('DROP TABLE rental_transport');
        $this->addSql('ALTER TABLE rental ADD address_id INT NOT NULL');
        $this->addSql('ALTER TABLE rental DROP system');
        $this->addSql('ALTER TABLE rental DROP celestial_object');
        $this->addSql('ALTER TABLE rental DROP longitude');
        $this->addSql('ALTER TABLE rental DROP latitude');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT fk_1619c27df5b7af75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1619c27df5b7af75 ON rental (address_id)');
        $this->addSql('ALTER TABLE "user" DROP uuid');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP DEFAULT');
        $this->addSql('ALTER TABLE report DROP CONSTRAINT FK_C42F7784F675F31B');
        $this->addSql('DROP INDEX IDX_C42F7784F675F31B');
        $this->addSql('ALTER TABLE report DROP author_id');
    }
}
