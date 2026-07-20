-- ============================================================
--  MIGRATION – à exécuter si vous avez déjà une base existante
--  (ne recréé pas les tables, ajoute seulement les colonnes manquantes)
-- ============================================================

-- 1. Créer la table operateurs si elle n'existe pas
CREATE TABLE IF NOT EXISTS operateurs (
    id                    INTEGER PRIMARY KEY AUTOINCREMENT,
    nom                   TEXT    NOT NULL UNIQUE,
    pourcentage_commission DECIMAL(5,2) NOT NULL DEFAULT 0
);

-- 2. Insérer les opérateurs de base
INSERT OR IGNORE INTO operateurs (nom, pourcentage_commission) VALUES
('Yas',    30.00),
('Airtel', 50.00),
('Orange', 20.00);

-- 3. Ajouter la colonne id_operateur à prefixes (ignore si déjà existe)
-- SQLite ne supporte pas "ADD COLUMN IF NOT EXISTS" nativement,
-- utiliser un try/catch dans votre code ou exécuter manuellement :
ALTER TABLE prefixes ADD COLUMN id_operateur INTEGER REFERENCES operateurs(id);

-- 4. Lier les préfixes existants aux opérateurs
UPDATE prefixes SET id_operateur = (SELECT id FROM operateurs WHERE nom = 'Yas')    WHERE prefixe IN ('034','038');
UPDATE prefixes SET id_operateur = (SELECT id FROM operateurs WHERE nom = 'Airtel') WHERE prefixe = '033';
UPDATE prefixes SET id_operateur = (SELECT id FROM operateurs WHERE nom = 'Orange') WHERE prefixe IN ('032','037');

-- 5. Insérer le préfixe 038 s'il manque
INSERT OR IGNORE INTO prefixes (prefixe, id_operateur)
VALUES ('038', (SELECT id FROM operateurs WHERE nom = 'Yas'));

-- 6. Ajouter commission à transactions
ALTER TABLE transactions ADD COLUMN commission REAL DEFAULT 0;

-- 7. Ajouter solde_apres à transactions
ALTER TABLE transactions ADD COLUMN solde_apres REAL;

-- 8. Corriger l'ancien barème retrait si besoin
-- (optionnel – exécuter si les frais sont incorrects)
-- UPDATE bareme_frais SET frais_fixe = 800 WHERE id_type_operation = (SELECT id FROM type_operations WHERE code='RET') AND montant_min = 50001;
