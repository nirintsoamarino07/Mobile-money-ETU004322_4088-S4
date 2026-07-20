<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * RetraitModel – gestion des retraits.
 *
 * Méthodes principales :
 *   insertRetrait($clientId, $amount, $frais, $soldeApres) : bool
 */
class RetraitModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_type_operation',
        'client_id_expediteur',
        'client_id_destinataire',
        'montant',
        'frais',
        'commission',
        'solde_apres',
        'date_transaction',
    ];

    /**
     * Enregistre un retrait dans les transactions.
     */
    public function insertRetrait(int $clientId, float $amount, float $frais, float $soldeApres): bool
    {
        $db     = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'RET')->get()->getRowArray();
        if (!$typeOp) return false;

        return (bool) $this->insert([
            'id_type_operation'      => $typeOp['id'],
            'client_id_expediteur'   => $clientId,
            'client_id_destinataire' => null,
            'montant'                => $amount,
            'frais'                  => $frais,
            'commission'             => 0,
            'solde_apres'            => $soldeApres,
            'date_transaction'       => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Calcule les frais de retrait selon le barème.
     */
    public function getFraisRetrait(float $amount): float
    {
        $db     = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'RET')->get()->getRowArray();
        if (!$typeOp) return 0.0;

        $bareme = $db->table('bareme_frais')
            ->where('id_type_operation', $typeOp['id'])
            ->where('montant_min <=', $amount)
            ->where('montant_max >=', $amount)
            ->get()->getRowArray();

        if (!$bareme) return 0.0;
        return (float) ($bareme['frais_fixe'] + ($amount * $bareme['frais_pourcentage'] / 100));
    }
}
