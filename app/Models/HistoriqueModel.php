<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * HistoriqueModel – récupération de l'historique des transactions.
 *
 * Méthodes principales :
 *   getHistoriqueClient($clientId)
 *   getHistoriqueOperateur($filters)
 */
class HistoriqueModel extends Model
{
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    /**
     * Retourne l'historique complet d'un client (expéditeur ou destinataire).
     *
     * @param int $clientId
     * @return array
     */
    public function getHistoriqueClient(int $clientId): array
    {
        $db = \Config\Database::connect();

        return $db->table('transactions')
            ->select('transactions.*,
                      type_operations.nom  as type_nom,
                      type_operations.code as type_code,
                      c_exp.telephone      as expediteur_tel,
                      c_dest.telephone     as destinataire_tel')
            ->join('type_operations', 'type_operations.id = transactions.id_type_operation')
            ->join('clients as c_exp',  'c_exp.id  = transactions.client_id_expediteur',   'left')
            ->join('clients as c_dest', 'c_dest.id = transactions.client_id_destinataire', 'left')
            ->where('transactions.client_id_expediteur',   $clientId)
            ->orWhere('transactions.client_id_destinataire', $clientId)
            ->orderBy('transactions.date_transaction', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Retourne l'historique opérateur avec filtres optionnels.
     *
     * @param array $filters  Keys: date_debut, date_fin, telephone, type_code
     * @return array
     */
    public function getHistoriqueOperateur(array $filters = []): array
    {
        $db = \Config\Database::connect();

        $query = $db->table('transactions')
            ->select('transactions.*,
                      type_operations.nom  as type_nom,
                      type_operations.code as type_code,
                      c_exp.telephone      as expediteur_tel,
                      c_dest.telephone     as destinataire_tel')
            ->join('type_operations', 'type_operations.id = transactions.id_type_operation')
            ->join('clients as c_exp',  'c_exp.id  = transactions.client_id_expediteur',   'left')
            ->join('clients as c_dest', 'c_dest.id = transactions.client_id_destinataire', 'left');

        if (!empty($filters['date_debut'])) {
            $query->where('transactions.date_transaction >=', $filters['date_debut'] . ' 00:00:00');
        }
        if (!empty($filters['date_fin'])) {
            $query->where('transactions.date_transaction <=', $filters['date_fin'] . ' 23:59:59');
        }
        if (!empty($filters['telephone'])) {
            $clientModel  = new ClientModel();
            $clientSearch = $clientModel->where('telephone', $filters['telephone'])->first();
            if ($clientSearch) {
                $query->groupStart()
                    ->where('transactions.client_id_expediteur',   $clientSearch['id'])
                    ->orWhere('transactions.client_id_destinataire', $clientSearch['id'])
                    ->groupEnd();
            } else {
                return [];
            }
        }
        if (!empty($filters['type_code'])) {
            $query->where('type_operations.code', $filters['type_code']);
        }
        return $query->orderBy('transactions.date_transaction', 'DESC')->get()->getResultArray();
    }
}
