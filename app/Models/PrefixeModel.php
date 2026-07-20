<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['prefixe', 'id_operateur'];

    /**
     * Get operator by prefix
     */
    public function getOperateurByPrefix(string $prefix): ?array
    {
        $operateurModel = new OperateurModel();
        return $operateurModel->getOperateurByPrefix($prefix);
    }
}
