<?php

namespace App\Controllers;

use App\Models\PrefixModel;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use App\Models\ClientModel;
use App\Models\TransactionModel;

class OperatorController extends BaseController
{
    public function index()
    {
        $prefixModel = new PrefixModel();
        $baremeFraisModel = new BaremeFraisModel();
        $typeOperationModel = new TypeOperationModel();
        $clientModel = new ClientModel();

        // 1. Valid prefixes
        $prefixes = $prefixModel->findAll();

        // 2. Fee scales
        $baremes = $baremeFraisModel->select('bareme_frais.*, type_operations.nom as type_nom, type_operations.code as type_code')
            ->join('type_operations', 'type_operations.id = bareme_frais.id_type_operation')
            ->orderBy('id_type_operation', 'ASC')
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        // All operation types
        $typeOperations = $typeOperationModel->findAll();

        // 3. Situation des gains
        $db = \Config\Database::connect();
        $gains = $db->table('transactions')
            ->select('type_operations.nom as type_nom, type_operations.code as type_code, SUM(transactions.frais) as total_gains')
            ->join('type_operations', 'type_operations.id = transactions.id_type_operation')
            ->groupBy('transactions.id_type_operation')
            ->get()
            ->getResultArray();

        $gainsMap = [];
        foreach ($typeOperations as $type) {
            $gainsMap[$type['code']] = [
                'nom' => $type['nom'],
                'total' => 0
            ];
        }
        foreach ($gains as $gain) {
            if (isset($gainsMap[$gain['type_code']])) {
                $gainsMap[$gain['type_code']]['total'] = $gain['total_gains'] ?? 0;
            }
        }

        // 4. Situation des comptes clients
        $clients = $clientModel->orderBy('telephone', 'ASC')->findAll();

        return view('operator/dashboard', [
            'prefixes' => $prefixes,
            'baremes' => $baremes,
            'typeOperations' => $typeOperations,
            'gains' => $gainsMap,
            'clients' => $clients
        ]);
    }

    public function addPrefix()
    {
        $prefix = $this->request->getPost('prefixe');
        if (!empty($prefix)) {
            $prefixModel = new PrefixModel();
            try {
                $prefixModel->insert(['prefixe' => $prefix]);
                session()->setFlashdata('success', 'Préfixe ajouté avec succès.');
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Erreur lors de l\'ajout (le préfixe existe peut-être déjà).');
            }
        }
        return redirect()->to(site_url('operator'));
    }

    public function deletePrefix($id)
    {
        $prefixModel = new PrefixModel();
        $prefixModel->delete($id);
        session()->setFlashdata('success', 'Préfixe supprimé avec succès.');
        return redirect()->to(site_url('operator'));
    }

    public function addBareme()
    {
        $idTypeOp = $this->request->getPost('id_type_operation');
        $min = $this->request->getPost('montant_min');
        $max = $this->request->getPost('montant_max');
        $fixe = $this->request->getPost('frais_fixe') ?: 0;
        $pct = $this->request->getPost('frais_pourcentage') ?: 0;

        if ($idTypeOp !== null && $min !== null && $max !== null) {
            $baremeModel = new BaremeFraisModel();
            $baremeModel->insert([
                'id_type_operation' => $idTypeOp,
                'montant_min' => $min,
                'montant_max' => $max,
                'frais_fixe' => $fixe,
                'frais_pourcentage' => $pct
            ]);
            session()->setFlashdata('success', 'Barème ajouté avec succès.');
        } else {
            session()->setFlashdata('error', 'Veuillez remplir tous les champs obligatoires.');
        }
        return redirect()->to(site_url('operator'));
    }

    public function editBareme()
    {
        $id = $this->request->getPost('id');
        $min = $this->request->getPost('montant_min');
        $max = $this->request->getPost('montant_max');
        $fixe = $this->request->getPost('frais_fixe') ?: 0;
        $pct = $this->request->getPost('frais_pourcentage') ?: 0;

        if ($id !== null && $min !== null && $max !== null) {
            $baremeModel = new BaremeFraisModel();
            $baremeModel->update($id, [
                'montant_min' => $min,
                'montant_max' => $max,
                'frais_fixe' => $fixe,
                'frais_pourcentage' => $pct
            ]);
            session()->setFlashdata('success', 'Barème mis à jour avec succès.');
        }
        return redirect()->to(site_url('operator'));
    }

    public function deleteBareme($id)
    {
        $baremeModel = new BaremeFraisModel();
        $baremeModel->delete($id);
        session()->setFlashdata('success', 'Barème supprimé avec succès.');
        return redirect()->to(site_url('operator'));
    }
}
