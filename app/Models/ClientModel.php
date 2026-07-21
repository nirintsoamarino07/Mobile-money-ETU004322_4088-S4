<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['telephone', 'solde', 'date_creation'];

    public function findByTelephone(string $telephone): ?array
    {
        return $this->where('telephone', $telephone)->first();
    }

    /**
     * Crée un nouveau client avec un solde de 0 Ar.
     *
     * @return int  ID du nouveau client
     */
    public function insertClient(string $telephone): int
    {
        return (int) $this->insert([
            'telephone'     => $telephone,
            'solde'         => 0.0,
            'date_creation' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getSolde(int $clientId): float
    {
        $client = $this->find($clientId);
        return $client ? (float) $client['solde'] : 0.0;
    }

    public function updateSolde(int $clientId, float $nouveauSolde): bool
    {
        return $this->update($clientId, ['solde' => $nouveauSolde]);
    }
}
