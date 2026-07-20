<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom', 'pourcentage_commission'];

    /**
     * Get operator by ID, or all operators if no ID is passed.
     */
    public function getOperateur(?int $id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }
        return $this->find($id);
    }

    /**
     * Get operator using prefix (3 digits)
     */
    public function getOperateurByPrefix(string $prefix): ?array
    {
        $db = \Config\Database::connect();
        $row = $db->table('prefixes')
            ->select('operateurs.*')
            ->join('operateurs', 'operateurs.id = prefixes.id_operateur')
            ->where('prefixes.prefixe', $prefix)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Récupère l'opérateur associé à un préfixe (3 premiers chiffres d'un numéro).
     */
    public function getOperateurByTelephone(string $telephone): ?array
    {
        $prefix = substr($telephone, 0, 3);
        return $this->getOperateurByPrefix($prefix);
    }

    /**
     * Récupère le pourcentage de commission d'un opérateur.
     */
    public function getCommission(int $idOperateur): float
    {
        $row = $this->find($idOperateur);
        return $row ? (float) $row['pourcentage_commission'] : 0.0;
    }

    /**
     * Alias requested by requirements
     */
    public function getCommissionOperateur(int $idOperateur): float
    {
        return $this->getCommission($idOperateur);
    }
}
