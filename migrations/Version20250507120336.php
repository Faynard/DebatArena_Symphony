<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507120336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE argument DROP FOREIGN KEY FK_D113B0ABA26AFB2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D113B0ABA26AFB2 ON argument
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument DROP user_validate_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE argument ADD user_validate_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE argument ADD CONSTRAINT FK_D113B0ABA26AFB2 FOREIGN KEY (user_validate_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D113B0ABA26AFB2 ON argument (user_validate_id)
        SQL);
    }
}
