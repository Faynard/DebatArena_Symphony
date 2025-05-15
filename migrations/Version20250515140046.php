<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250515140046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE argument (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, camp_id INT NOT NULL, main_argument_id INT DEFAULT NULL, text VARCHAR(800) NOT NULL, creation_date DATETIME DEFAULT NULL, INDEX IDX_D113B0AA76ED395 (user_id), INDEX IDX_D113B0A77075ABB (camp_id), INDEX IDX_D113B0AE95747B7 (main_argument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE camp (id INT AUTO_INCREMENT NOT NULL, debate_id INT NOT NULL, name_camp VARCHAR(255) NOT NULL, INDEX IDX_C194423039A6B6F6 (debate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name_category VARCHAR(255) NOT NULL, description_category VARCHAR(800) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE debate (id INT AUTO_INCREMENT NOT NULL, user_created_id INT NOT NULL, name_debate VARCHAR(255) NOT NULL, description_debate VARCHAR(800) NOT NULL, is_valid TINYINT(1) NOT NULL, creation_date DATE NOT NULL, INDEX IDX_DDC4A368F987D8A8 (user_created_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE debate_category (debate_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_7D072B1B39A6B6F6 (debate_id), INDEX IDX_7D072B1B12469DE2 (category_id), PRIMARY KEY(debate_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, argument_id INT NOT NULL, report_date DATE NOT NULL, is_valid TINYINT(1) NOT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F77843DD48F21 (argument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sanction (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, argument_id INT NOT NULL, sanction_date DATE NOT NULL, reason VARCHAR(800) NOT NULL, INDEX IDX_6D6491AFA76ED395 (user_id), INDEX IDX_6D6491AF3DD48F21 (argument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(320) NOT NULL, pseudo VARCHAR(20) NOT NULL, password VARCHAR(150) NOT NULL, created_date DATE NOT NULL, is_banned TINYINT(1) NOT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, argument_id INT NOT NULL, vote_date DATE NOT NULL, INDEX IDX_518B7ACFA76ED395 (user_id), INDEX IDX_518B7ACF3DD48F21 (argument_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument ADD CONSTRAINT FK_D113B0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument ADD CONSTRAINT FK_D113B0A77075ABB FOREIGN KEY (camp_id) REFERENCES camp (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument ADD CONSTRAINT FK_D113B0AE95747B7 FOREIGN KEY (main_argument_id) REFERENCES argument (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE camp ADD CONSTRAINT FK_C194423039A6B6F6 FOREIGN KEY (debate_id) REFERENCES debate (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE debate ADD CONSTRAINT FK_DDC4A368F987D8A8 FOREIGN KEY (user_created_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE debate_category ADD CONSTRAINT FK_7D072B1B39A6B6F6 FOREIGN KEY (debate_id) REFERENCES debate (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE debate_category ADD CONSTRAINT FK_7D072B1B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report ADD CONSTRAINT FK_C42F77843DD48F21 FOREIGN KEY (argument_id) REFERENCES argument (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sanction ADD CONSTRAINT FK_6D6491AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sanction ADD CONSTRAINT FK_6D6491AF3DD48F21 FOREIGN KEY (argument_id) REFERENCES argument (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF3DD48F21 FOREIGN KEY (argument_id) REFERENCES argument (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE argument DROP FOREIGN KEY FK_D113B0AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument DROP FOREIGN KEY FK_D113B0A77075ABB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument DROP FOREIGN KEY FK_D113B0AE95747B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE camp DROP FOREIGN KEY FK_C194423039A6B6F6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE debate DROP FOREIGN KEY FK_DDC4A368F987D8A8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE debate_category DROP FOREIGN KEY FK_7D072B1B39A6B6F6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE debate_category DROP FOREIGN KEY FK_7D072B1B12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE report DROP FOREIGN KEY FK_C42F77843DD48F21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sanction DROP FOREIGN KEY FK_6D6491AFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sanction DROP FOREIGN KEY FK_6D6491AF3DD48F21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF3DD48F21
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE argument
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE camp
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE debate
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE debate_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE report
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sanction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE votes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
