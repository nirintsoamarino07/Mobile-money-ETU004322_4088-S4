<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TransfertModel – gestion des transferts simples et multiples.
 *
 * Méthodes principales :
 *   insertTransfert()
 *   insertMultiTransfert()
 */
class TransfertModel extends Model
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
     * Enregistre un transfert simple.
     */
    public function insertTransfert(
        int   $expediteurId,
        int   $destinataireId,
        float $amount,
        float $frais,
        float $commission,
        float $soldeApres
    ): bool {
        $db     = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'TRA')->get()->getRowArray();
        if (!$typeOp) return false;

        return (bool) $this->insert([
            'id_type_operation'      => $typeOp['id'],
            'client_id_expediteur'   => $expediteurId,
            'client_id_destinataire' => $destinataireId,
            'montant'                => $amount,
            'frais'                  => $frais,
            'commission'             => $commission,
            'solde_apres'            => $soldeApres,
            'date_transaction'       => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Enregistre un transfert parmi plusieurs (utilisé dans l'envoi multiple).
     * Même signature que insertTransfert.
     */
    public function insertMultiTransfert(
        int   $expediteurId,
        int   $destinataireId,
        float $amount,
        float $frais,
        float $soldeApres
    ): bool {
        return $this->insertTransfert($expediteurId, $destinataireId, $amount, $frais, 0.0, $soldeApres);
    }

    /**
     * Calcule les frais de transfert selon le barème.
     */
    public function getFraisByMontant(float $amount): float
    {
        $db     = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'TRA')->get()->getRowArray();
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
