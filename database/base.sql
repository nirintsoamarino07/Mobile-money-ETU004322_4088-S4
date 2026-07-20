-- SQLite3 Database Schema for Mobile Money Simulator

PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS prefixes;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS type_operations;
DROP TABLE IF EXISTS bareme_frais;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS sessions;
DROP VIEW IF EXISTS vue_situation_clients;
DROP VIEW IF EXISTS vue_transactions;
DROP VIEW IF EXISTS vue_gain_operateur;

PRAGMA foreign_keys = ON;

-- 1. Table prefixes
CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT UNIQUE NOT NULL
);

-- 2. Table clients
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT UNIQUE NOT NULL,   
    solde REAL DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Table type_operations
CREATE TABLE type_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE
);

-- 4. Table bareme_frais
CREATE TABLE bareme_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais_fixe REAL DEFAULT 0,
    frais_pourcentage REAL DEFAULT 0,
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id)
);

-- 5. Table transactions
CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER,
    client_id_expediteur INTEGER,
    client_id_destinataire INTEGER,
    montant REAL,
    frais REAL,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id),
    FOREIGN KEY (client_id_expediteur) REFERENCES clients(id),
    FOREIGN KEY (client_id_destinataire) REFERENCES clients(id)
);

-- 6. Table sessions
CREATE TABLE sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER,
    token TEXT,
    date_connexion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- --------------------------------------------------------
-- Seed Data
-- --------------------------------------------------------

-- Prefixes
INSERT INTO prefixes (prefixe) VALUES ('034'), ('032'), ('033'), ('037');

-- Type of operations
INSERT INTO type_operations (nom, code) VALUES
('Dépôt', 'DEP'),
('Retrait', 'RET'),
('Transfert', 'TRA');

-- Default baremes
-- DEP: 0 to 1,000,000 -> 0 Ar
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais_fixe, frais_pourcentage) VALUES
((SELECT id FROM type_operations WHERE code = 'DEP'), 0, 1000000, 0, 0);

-- RET:
-- 0 to 10,000 -> 100 Ar
-- 10,001 to 50,000 -> 500 Ar
-- 50,001 to 100,000 -> 1,000 Ar
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais_fixe, frais_pourcentage) VALUES
((SELECT id FROM type_operations WHERE code = 'RET'), 0, 10000, 100, 0),
((SELECT id FROM type_operations WHERE code = 'RET'), 10001, 50000, 500, 0),
((SELECT id FROM type_operations WHERE code = 'RET'), 50001, 100000, 1000, 0);

-- TRA:
-- 0 to 10,000 -> 200 Ar
-- 10,001 to 50,000 -> 700 Ar
-- 50,001 to 100,000 -> 1,500 Ar
INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais_fixe, frais_pourcentage) VALUES
((SELECT id FROM type_operations WHERE code = 'TRA'), 0, 10000, 200, 0),
((SELECT id FROM type_operations WHERE code = 'TRA'), 10001, 50000, 700, 0),
((SELECT id FROM type_operations WHERE code = 'TRA'), 50001, 100000, 1500, 0);

-- Clients initial values
INSERT INTO clients (telephone, solde) VALUES
('0331111111', 30000.0),
('0372222222', 10000.0);

-- Views matching new schema
CREATE VIEW vue_situation_clients AS
SELECT id, telephone, solde FROM clients;

CREATE VIEW vue_transactions AS
SELECT
t.id,
c1.telephone AS source,
c2.telephone AS destination,
t.montant,
t.frais,
t.date_transaction
FROM transactions t
LEFT JOIN clients c1 ON t.client_id_expediteur = c1.id
LEFT JOIN clients c2 ON t.client_id_destinataire = c2.id;

CREATE VIEW vue_gain_operateur AS
SELECT SUM(frais) AS total_gain FROM transactions;
