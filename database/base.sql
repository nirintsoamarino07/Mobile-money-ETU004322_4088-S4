CREATE TABLE IF NOT EXISTS operateurs (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom  TEXT NOT NULL UNIQUE,
    pourcentage_commission DECIMAL(5,2) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS prefixes (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT UNIQUE NOT NULL,
    id_operateur INTEGER,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id)
);

CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT UNIQUE NOT NULL,
    solde REAL DEFAULT 0, 
    epa_pourcentage REAL DEFAULT 0,
    date_creation  DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS type_operations (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    nom  TEXT NOT NULL,
    code TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS bareme_frais (
    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation  INTEGER,
    montant_min        REAL NOT NULL,
    montant_max        REAL NOT NULL,
    frais_fixe         REAL DEFAULT 0,
    frais_pourcentage  REAL DEFAULT 0, 
    FOREIGN KEY (id_type_operation) REFERENCES type_operations(id)
);

CREATE TABLE IF NOT EXISTS transactions (
    id                    INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation     INTEGER,
    client_id_expediteur  INTEGER,
    client_id_destinataire INTEGER,
    montant               REAL,
    frais                 REAL DEFAULT 0,
    commission            REAL DEFAULT 0,
    solde_apres           REAL,
    date_transaction      DATETIME DEFAULT CURRENT_TIMESTAMP, 

    FOREIGN KEY (id_type_operation)      REFERENCES type_operations(id),
    FOREIGN KEY (client_id_expediteur)   REFERENCES clients(id),
    FOREIGN KEY (client_id_destinataire) REFERENCES clients(id)
);

CREATE TABLE IF NOT EXISTS sessions_client (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id      INTEGER,
    token          TEXT,
    date_connexion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

INSERT INTO operateurs (nom, pourcentage_commission) VALUES
('Yas',    30.00),
('Airtel', 50.00),
('Orange', 20.00);

INSERT INTO prefixes (prefixe, id_operateur) VALUES
('034', (SELECT id FROM operateurs WHERE nom = 'Yas')),
('038', (SELECT id FROM operateurs WHERE nom = 'Yas')),
('033', (SELECT id FROM operateurs WHERE nom = 'Airtel')),
('032', (SELECT id FROM operateurs WHERE nom = 'Orange')),
('037', (SELECT id FROM operateurs WHERE nom = 'Orange'));

INSERT INTO type_operations (nom, code) VALUES
('Dépôt',    'DEP'),
('Retrait',  'RET'),
('Transfert','TRA');

INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais_fixe, frais_pourcentage) VALUES
((SELECT id FROM type_operations WHERE code = 'DEP'), 0, 9999999, 0, 0);

INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais_fixe, frais_pourcentage) VALUES
((SELECT id FROM type_operations WHERE code = 'RET'),        0,  10000, 100, 0),
((SELECT id FROM type_operations WHERE code = 'RET'),    10001,  50000, 500, 0),
((SELECT id FROM type_operations WHERE code = 'RET'),    50001, 100000, 800, 0);

INSERT INTO bareme_frais (id_type_operation, montant_min, montant_max, frais_fixe, frais_pourcentage) VALUES
((SELECT id FROM type_operations WHERE code = 'TRA'),        0,  10000, 200, 0),
((SELECT id FROM type_operations WHERE code = 'TRA'),    10001,  50000, 500, 0),
((SELECT id FROM type_operations WHERE code = 'TRA'),    50001, 100000, 800, 0);

INSERT INTO clients (telephone, solde, epa_pourcentage) VALUES
('0321111111', 100000.0, '5%'),
('0341234567', 50000.0 '15%'),
('0331111111', 30000.0 '10%'),
('0372222222', 10000.0 '6%');


CREATE VIEW IF NOT EXISTS vue_situation_clients AS
SELECT id, telephone, solde FROM clients;

CREATE VIEW IF NOT EXISTS vue_transactions AS
SELECT
    t.id,
    c1.telephone AS source,
    c2.telephone AS destination,
    t.montant,
    t.frais,
    t.commission,
    t.solde_apres,
    t.date_transaction
FROM transactions t
LEFT JOIN clients c1 ON t.client_id_expediteur   = c1.id
LEFT JOIN clients c2 ON t.client_id_destinataire = c2.id;

CREATE VIEW IF NOT EXISTS vue_gain_operateur AS
SELECT
    SUM(frais)       AS total_frais,
    SUM(commission)  AS total_commission,
    SUM(frais) + SUM(commission) AS total_gain
FROM transactions;
