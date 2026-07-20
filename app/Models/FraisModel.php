<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table            = 'bareme_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_type_operation', 'montant_min', 'montant_max', 'frais_fixe', 'frais_pourcentage'];

    /**
     * Get fees by operation type and amount
     */
    public function getFraisByMontant(string $typeCode, float $amount): float
    {
        $db = \Config\Database::connect();

        $typeOp = $db->table('type_operations')
            ->where('code', $typeCode)
            ->get()->getRowArray();

        if (!$typeOp) {
            return 0.0;
        }

        $bareme = $db->table('bareme_frais')
            ->where('id_type_operation', $typeOp['id'])
            ->where('montant_min <=', $amount)
            ->where('montant_max >=', $amount)
            ->get()->getRowArray();

        if (!$bareme) {
            return 0.0;
        }

        return (float) ($bareme['frais_fixe'] + ($amount * $bareme['frais_pourcentage'] / 100));
    }
}
