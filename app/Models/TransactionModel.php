<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
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
        'date_transaction'
    ];
}
