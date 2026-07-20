<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DepotModel – gestion des dépôts.
 *
 * Méthodes principales :
 *   insertDepot($clientId, $amount, $frais, $soldeApres) : bool
 */
class DepotModel extends Model
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
     * Enregistre un dépôt dans les transactions.
     */
    public function insertDepot(int $clientId, float $amount, float $frais, float $soldeApres): bool
    {
        $db     = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'DEP')->get()->getRowArray();
        if (!$typeOp) return false;

        return (bool) $this->insert([
            'id_type_operation'      => $typeOp['id'],
            'client_id_expediteur'   => null,
            'client_id_destinataire' => $clientId,
            'montant'                => $amount,
            'frais'                  => $frais,
            'commission'             => 0,
            'solde_apres'            => $soldeApres,
            'date_transaction'       => date('Y-m-d H:i:s'),
        ]);
    }
}
