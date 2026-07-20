<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        $db = \Config\Database::connect();

        // ── Opérateurs ───────────────────────────────────────────────
        $db->table('operateurs')->truncate();
        $db->table('operateurs')->insertBatch([
            ['nom' => 'Yas',    'pourcentage_commission' => 30.00],
            ['nom' => 'Airtel', 'pourcentage_commission' => 50.00],
            ['nom' => 'Orange', 'pourcentage_commission' => 20.00],
        ]);

        $idYas    = $db->table('operateurs')->where('nom', 'Yas')   ->get()->getRowArray()['id'];
        $idAirtel = $db->table('operateurs')->where('nom', 'Airtel')->get()->getRowArray()['id'];
        $idOrange = $db->table('operateurs')->where('nom', 'Orange')->get()->getRowArray()['id'];

        // ── Préfixes ─────────────────────────────────────────────────
        $db->table('prefixes')->truncate();
        $db->table('prefixes')->insertBatch([
            ['prefixe' => '034', 'id_operateur' => $idYas],
            ['prefixe' => '038', 'id_operateur' => $idYas],
            ['prefixe' => '033', 'id_operateur' => $idAirtel],
            ['prefixe' => '032', 'id_operateur' => $idOrange],
            ['prefixe' => '037', 'id_operateur' => $idOrange],
        ]);

        // ── Types d'opérations ───────────────────────────────────────
        $db->table('type_operations')->truncate();
        $db->table('type_operations')->insertBatch([
            ['nom' => 'Dépôt',     'code' => 'DEP'],
            ['nom' => 'Retrait',   'code' => 'RET'],
            ['nom' => 'Transfert', 'code' => 'TRA'],
        ]);

        $idDep = $db->table('type_operations')->where('code', 'DEP')->get()->getRowArray()['id'];
        $idRet = $db->table('type_operations')->where('code', 'RET')->get()->getRowArray()['id'];
        $idTra = $db->table('type_operations')->where('code', 'TRA')->get()->getRowArray()['id'];

        // ── Barème de frais ───────────────────────────────────────────
        $db->table('bareme_frais')->truncate();
        $db->table('bareme_frais')->insertBatch([
            // Dépôt – gratuit
            ['id_type_operation' => $idDep, 'montant_min' =>      0, 'montant_max' => 9999999, 'frais_fixe' =>    0, 'frais_pourcentage' => 0],
            // Retrait
            ['id_type_operation' => $idRet, 'montant_min' =>      0, 'montant_max' =>   10000, 'frais_fixe' =>  100, 'frais_pourcentage' => 0],
            ['id_type_operation' => $idRet, 'montant_min' =>  10001, 'montant_max' =>   50000, 'frais_fixe' =>  500, 'frais_pourcentage' => 0],
            ['id_type_operation' => $idRet, 'montant_min' =>  50001, 'montant_max' =>  100000, 'frais_fixe' =>  800, 'frais_pourcentage' => 0],
            // Transfert
            ['id_type_operation' => $idTra, 'montant_min' =>      0, 'montant_max' =>   10000, 'frais_fixe' =>  200, 'frais_pourcentage' => 0],
            ['id_type_operation' => $idTra, 'montant_min' =>  10001, 'montant_max' =>   50000, 'frais_fixe' =>  500, 'frais_pourcentage' => 0],
            ['id_type_operation' => $idTra, 'montant_min' =>  50001, 'montant_max' =>  100000, 'frais_fixe' =>  800, 'frais_pourcentage' => 0],
        ]);

        // ── Clients de démonstration ──────────────────────────────────
        $db->table('clients')->truncate();
        $db->table('clients')->insertBatch([
            ['telephone' => '0321111111', 'solde' => 100000.0, 'date_creation' => date('Y-m-d H:i:s')],
            ['telephone' => '0341234567', 'solde' =>  50000.0, 'date_creation' => date('Y-m-d H:i:s')],
            ['telephone' => '0331111111', 'solde' =>  30000.0, 'date_creation' => date('Y-m-d H:i:s')],
            ['telephone' => '0372222222', 'solde' =>  10000.0, 'date_creation' => date('Y-m-d H:i:s')],
        ]);

        echo "✅ MainSeeder terminé avec succès.\n";
    }
}
