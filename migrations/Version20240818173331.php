<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240818173331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balances (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, crypto_id INT DEFAULT NULL, balance NUMERIC(20, 10) NOT NULL, INDEX IDX_41A7E40FA76ED395 (user_id), INDEX IDX_41A7E40FE9571A63 (crypto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balances ADD CONSTRAINT FK_41A7E40FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE balances ADD CONSTRAINT FK_41A7E40FE9571A63 FOREIGN KEY (crypto_id) REFERENCES cryptocurrencies (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE balances DROP FOREIGN KEY FK_41A7E40FA76ED395');
        $this->addSql('ALTER TABLE balances DROP FOREIGN KEY FK_41A7E40FE9571A63');
        $this->addSql('DROP TABLE balances');
    }
}
