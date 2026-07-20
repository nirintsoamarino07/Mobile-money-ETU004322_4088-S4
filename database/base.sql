CREATE TABLE operateurs (
id INTEGER PRIMARY KEY AUTOINCREMENT,
nom TEXT NOT NULL,
prefixe TEXT UNIQUE NOT NULL
);

CREATE TABLE clients (
id INTEGER PRIMARY KEY AUTOINCREMENT,
telephone TEXT UNIQUE NOT NULL,   
solde REAL DEFAULT 0,
date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE types_operations (
id INTEGER PRIMARY KEY AUTOINCREMENT,
nom TEXT NOT NULL
);

CREATE TABLE baremes_frais (
id INTEGER PRIMARY KEY AUTOINCREMENT,
type_operation_id INTEGER,
montant_min REAL,
montant_max REAL,
frais REAL,
FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

CREATE TABLE transactions (
id INTEGER PRIMARY KEY AUTOINCREMENT,
type_operation_id INTEGER,
client_source_id INTEGER,
client_destination_id INTEGER,
montant REAL,
frais REAL,
date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (type_operation_id) REFERENCES types_operations(id),
FOREIGN KEY (client_source_id) REFERENCES clients(id),
FOREIGN KEY (client_destination_id) REFERENCES clients(id)
);

CREATE TABLE sessions (
id INTEGER PRIMARY KEY AUTOINCREMENT,
client_id INTEGER,
token TEXT,
date_connexion DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (client_id) REFERENCES clients(id)
);

INSERT INTO operateurs (nom, prefixe) VALUES
('Telma', '034'),
('Orange', '032'),
('Airtel', '033'),
('Yas', '037');

-- TYPES OPERATIONS
INSERT INTO types_operations (nom) VALUES
('depot'),
('retrait'),
('transfert');

INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(1, 0, 1000000, 0);

INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(2, 0, 10000, 100),
(2, 10001, 50000, 500),
(2, 50001, 100000, 1000);

INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(3, 0, 10000, 200),
(3, 10001, 50000, 700),
(3, 50001, 100000, 1500);

INSERT INTO clients (telephone, solde) VALUES
('0331111111', 30000),
('0372222222', 10000);

CREATE VIEW vue_situation_clients AS
SELECT id, telephone, solde FROM clients;

-- Historique transactions
CREATE VIEW vue_transactions AS
SELECT
t.id,
c1.telephone AS source,
c2.telephone AS destination,
t.montant,
t.frais,
t.date_transaction
FROM transactions t
LEFT JOIN clients c1 ON t.client_source_id = c1.id
LEFT JOIN clients c2 ON t.client_destination_id = c2.id;

CREATE VIEW vue_gain_operateur AS
SELECT SUM(frais) AS total_gain FROM transactions;