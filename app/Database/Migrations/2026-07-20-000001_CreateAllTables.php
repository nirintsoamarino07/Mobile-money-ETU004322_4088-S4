<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateursTable extends Migration
{
    public function up(): void
    {
        // 1. operateurs
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'unique'     => true,
            ],
            'pourcentage_commission' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('operateurs', true);

        // 2. prefixes
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'prefixe' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => false,
                'unique'     => true,
            ],
            'id_operateur' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('prefixes', true);

        // 3. clients
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'telephone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'unique'     => true,
            ],
            'solde' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'date_creation' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('clients', true);

        // 4. type_operations
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
                'unique'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('type_operations', true);

        // 5. bareme_frais
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_type_operation' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
            ],
            'montant_min' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'montant_max' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'frais_fixe' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'frais_pourcentage' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bareme_frais', true);

        // 6. transactions
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_type_operation' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
            ],
            'client_id_expediteur' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
            ],
            'client_id_destinataire' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => true,
            ],
            'montant' => [
                'type' => 'REAL',
                'null' => true,
            ],
            'frais' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'commission' => [
                'type'    => 'REAL',
                'default' => 0,
            ],
            'solde_apres' => [
                'type' => 'REAL',
                'null' => true,
            ],
            'date_transaction' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transactions', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('transactions',   true);
        $this->forge->dropTable('bareme_frais',   true);
        $this->forge->dropTable('type_operations',true);
        $this->forge->dropTable('clients',        true);
        $this->forge->dropTable('prefixes',       true);
        $this->forge->dropTable('operateurs',     true);
    }
}
