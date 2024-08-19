<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240819054512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute le trigger after_transaction_insert pour mettre à jour la colonne average_price dans la table balances';
    }

    public function up(Schema $schema): void
    {
        // Ajout du trigger
        $this->addSql('
            CREATE TRIGGER after_transaction_insert
            AFTER INSERT ON transactions
            FOR EACH ROW
            BEGIN
                DECLARE current_balance DECIMAL(20, 10);
                DECLARE current_average_price DECIMAL(20, 10);
                DECLARE new_balance DECIMAL(20, 10);
                DECLARE new_average_price DECIMAL(20, 10);

                -- Vérifier si une balance existe déjà pour cet utilisateur et cette crypto
                SELECT balance, average_price INTO current_balance, current_average_price
                FROM balances
                WHERE user_id = NEW.user_id AND crypto_id = NEW.crypto_id;

                -- Si une balance existe, la mettre à jour
                IF current_balance IS NOT NULL THEN
                    IF NEW.transaction_type = \'buy\' THEN
                        SET new_balance = current_balance + NEW.quantity;
                        SET new_average_price = ((current_balance * current_average_price) + (NEW.quantity * NEW.price_at_transaction_in_usdt)) / new_balance;
                        UPDATE balances
                        SET balance = new_balance, average_price = new_average_price
                        WHERE user_id = NEW.user_id AND crypto_id = NEW.crypto_id;
                    ELSEIF NEW.transaction_type = \'sell\' THEN
                        SET new_balance = current_balance - NEW.quantity;
                        IF new_balance = 0 THEN
                            SET new_average_price = 0;
                        ELSE
                            SET new_average_price = current_average_price;
                        END IF;
                        UPDATE balances
                        SET balance = new_balance, average_price = new_average_price
                        WHERE user_id = NEW.user_id AND crypto_id = NEW.crypto_id;
                    END IF;
                -- Sinon, insérer une nouvelle balance
                ELSE
                    IF NEW.transaction_type = \'buy\' THEN
                        INSERT INTO balances (user_id, crypto_id, balance, average_price)
                        VALUES (NEW.user_id, NEW.crypto_id, NEW.quantity, NEW.price_at_transaction_in_usdt);
                    ELSEIF NEW.transaction_type = \'sell\' THEN
                        INSERT INTO balances (user_id, crypto_id, balance, average_price)
                        VALUES (NEW.user_id, NEW.crypto_id, -NEW.quantity, 0);
                    END IF;
                END IF;
            END;
        ');
    }

    public function down(Schema $schema): void
    {
        // Suppression du trigger
        $this->addSql('DROP TRIGGER IF EXISTS after_transaction_insert');
    }
}