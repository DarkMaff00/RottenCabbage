<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516192734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE favourite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE favourite (id INT NOT NULL, user_id VARCHAR(255) NOT NULL, movie_id INT NOT NULL, is_favourite BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62A2CA19291A82DC ON favourite (user_id)');
        $this->addSql('CREATE INDEX IDX_62A2CA198F93B6FC ON favourite (movie_id)');
        $this->addSql('CREATE TABLE movie (id INT NOT NULL, rate INT NOT NULL, is_movie BOOLEAN NOT NULL, num_of_ratings INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rate (id INT NOT NULL, user_id VARCHAR(255) NOT NULL, movie_id INT NOT NULL, rate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DFEC3F39291A82DC ON rate (user_id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F398F93B6FC ON rate (movie_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, movie_id INT NOT NULL, user_id VARCHAR(255) NOT NULL, add_date DATE NOT NULL, description VARCHAR(200) NOT NULL, num_of_likes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C68F93B6FC ON review (movie_id)');
        $this->addSql('CREATE INDEX IDX_794381C6291A82DC ON review (user_id)');
        $this->addSql('CREATE TABLE "user" (id VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(70) NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_user (user_source VARCHAR(255) NOT NULL, user_target VARCHAR(255) NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX IDX_F7129A803AD8644E ON user_user (user_source)');
        $this->addSql('CREATE INDEX IDX_F7129A80233D34C1 ON user_user (user_target)');
        $this->addSql('CREATE TABLE user_review (user_id VARCHAR(255) NOT NULL, review_id INT NOT NULL, PRIMARY KEY(user_id, review_id))');
        $this->addSql('CREATE INDEX IDX_1C119AFBA76ED395 ON user_review (user_id)');
        $this->addSql('CREATE INDEX IDX_1C119AFB3E2E969B ON user_review (review_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE favourite ADD CONSTRAINT FK_62A2CA19291A82DC FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favourite ADD CONSTRAINT FK_62A2CA198F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F39291A82DC FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F398F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6291A82DC FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT FK_1C119AFB3E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE favourite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE favourite DROP CONSTRAINT FK_62A2CA19291A82DC');
        $this->addSql('ALTER TABLE favourite DROP CONSTRAINT FK_62A2CA198F93B6FC');
        $this->addSql('ALTER TABLE rate DROP CONSTRAINT FK_DFEC3F39291A82DC');
        $this->addSql('ALTER TABLE rate DROP CONSTRAINT FK_DFEC3F398F93B6FC');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C68F93B6FC');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6291A82DC');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE user_review DROP CONSTRAINT FK_1C119AFBA76ED395');
        $this->addSql('ALTER TABLE user_review DROP CONSTRAINT FK_1C119AFB3E2E969B');
        $this->addSql('DROP TABLE favourite');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE user_review');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
