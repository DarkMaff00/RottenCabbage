<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425182254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE following (user_source UUID NOT NULL, user_target UUID NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX IDX_71BF8DE33AD8644E ON following (user_source)');
        $this->addSql('CREATE INDEX IDX_71BF8DE3233D34C1 ON following (user_target)');
        $this->addSql('COMMENT ON COLUMN following.user_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN following.user_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE favourite (user_id UUID NOT NULL, movie_id INT NOT NULL, PRIMARY KEY(user_id, movie_id))');
        $this->addSql('CREATE INDEX IDX_62A2CA19A76ED395 ON favourite (user_id)');
        $this->addSql('CREATE INDEX IDX_62A2CA198F93B6FC ON favourite (movie_id)');
        $this->addSql('COMMENT ON COLUMN favourite.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE like_review (user_id UUID NOT NULL, review_id INT NOT NULL, PRIMARY KEY(user_id, review_id))');
        $this->addSql('CREATE INDEX IDX_479A9234A76ED395 ON like_review (user_id)');
        $this->addSql('CREATE INDEX IDX_479A92343E2E969B ON like_review (review_id)');
        $this->addSql('COMMENT ON COLUMN like_review.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE33AD8644E FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE following ADD CONSTRAINT FK_71BF8DE3233D34C1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favourite ADD CONSTRAINT FK_62A2CA19A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favourite ADD CONSTRAINT FK_62A2CA198F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE like_review ADD CONSTRAINT FK_479A9234A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE like_review ADD CONSTRAINT FK_479A92343E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_review DROP CONSTRAINT fk_1c119afba76ed395');
        $this->addSql('ALTER TABLE user_review DROP CONSTRAINT fk_1c119afb3e2e969b');
        $this->addSql('ALTER TABLE user_movie DROP CONSTRAINT fk_ff9c0937a76ed395');
        $this->addSql('ALTER TABLE user_movie DROP CONSTRAINT fk_ff9c09378f93b6fc');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT fk_f7129a803ad8644e');
        $this->addSql('ALTER TABLE user_user DROP CONSTRAINT fk_f7129a80233d34c1');
        $this->addSql('DROP TABLE user_review');
        $this->addSql('DROP TABLE user_movie');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64912595e8');
        $this->addSql('DROP INDEX uniq_8d93d64912595e8');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_details_id_id TO user_details_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6491C7DC1CE FOREIGN KEY (user_details_id) REFERENCES user_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491C7DC1CE ON "user" (user_details_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE user_review (user_id UUID NOT NULL, review_id INT NOT NULL, PRIMARY KEY(user_id, review_id))');
        $this->addSql('CREATE INDEX idx_1c119afb3e2e969b ON user_review (review_id)');
        $this->addSql('CREATE INDEX idx_1c119afba76ed395 ON user_review (user_id)');
        $this->addSql('COMMENT ON COLUMN user_review.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_movie (user_id UUID NOT NULL, movie_id INT NOT NULL, PRIMARY KEY(user_id, movie_id))');
        $this->addSql('CREATE INDEX idx_ff9c09378f93b6fc ON user_movie (movie_id)');
        $this->addSql('CREATE INDEX idx_ff9c0937a76ed395 ON user_movie (user_id)');
        $this->addSql('COMMENT ON COLUMN user_movie.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_user (user_source UUID NOT NULL, user_target UUID NOT NULL, PRIMARY KEY(user_source, user_target))');
        $this->addSql('CREATE INDEX idx_f7129a80233d34c1 ON user_user (user_target)');
        $this->addSql('CREATE INDEX idx_f7129a803ad8644e ON user_user (user_source)');
        $this->addSql('COMMENT ON COLUMN user_user.user_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_user.user_target IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT fk_1c119afba76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_review ADD CONSTRAINT fk_1c119afb3e2e969b FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT fk_ff9c0937a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT fk_ff9c09378f93b6fc FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT fk_f7129a803ad8644e FOREIGN KEY (user_source) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT fk_f7129a80233d34c1 FOREIGN KEY (user_target) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE following DROP CONSTRAINT FK_71BF8DE33AD8644E');
        $this->addSql('ALTER TABLE following DROP CONSTRAINT FK_71BF8DE3233D34C1');
        $this->addSql('ALTER TABLE favourite DROP CONSTRAINT FK_62A2CA19A76ED395');
        $this->addSql('ALTER TABLE favourite DROP CONSTRAINT FK_62A2CA198F93B6FC');
        $this->addSql('ALTER TABLE like_review DROP CONSTRAINT FK_479A9234A76ED395');
        $this->addSql('ALTER TABLE like_review DROP CONSTRAINT FK_479A92343E2E969B');
        $this->addSql('DROP TABLE following');
        $this->addSql('DROP TABLE favourite');
        $this->addSql('DROP TABLE like_review');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6491C7DC1CE');
        $this->addSql('DROP INDEX UNIQ_8D93D6491C7DC1CE');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_details_id TO user_details_id_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64912595e8 FOREIGN KEY (user_details_id_id) REFERENCES user_info (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64912595e8 ON "user" (user_details_id_id)');
    }
}
