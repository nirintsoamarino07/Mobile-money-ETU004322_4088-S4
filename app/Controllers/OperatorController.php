<?php

namespace App\Controllers;

use App\Models\PrefixModel;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\OperateurModel;
use App\Models\HistoriqueModel;

class OperatorController extends BaseController
{
    // ── 1. INDEX (Dashboard principal) ──────────────────────
    public function index()
    {
        $prefixModel = new PrefixModel();
        $baremeFraisModel = new BaremeFraisModel();
        $typeOperationModel = new TypeOperationModel();
        $clientModel = new ClientModel();
        $operateurModel = new OperateurModel();
        $db = \Config\Database::connect();

        $prefixes = $db->table('prefixes')
            ->select('prefixes.*, operateurs.nom as operateur_nom')
            ->join('operateurs', 'operateurs.id = prefixes.id_operateur', 'left')
            ->orderBy('prefixes.prefixe', 'ASC')
            ->get()->getResultArray();

        $baremes = $baremeFraisModel
            ->select('bareme_frais.*, type_operations.nom as type_nom, type_operations.code as type_code')
            ->join('type_operations', 'type_operations.id = bareme_frais.id_type_operation')
            ->orderBy('id_type_operation', 'ASC')
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        $typeOperations = $typeOperationModel->findAll();
        $operateurs = $operateurModel->findAll();

        $gains = $db->table('transactions')
            ->select('type_operations.nom as type_nom, type_operations.code as type_code,
                      COUNT(transactions.id) as nb_transactions,
                      SUM(transactions.frais) as total_frais,
                      SUM(transactions.commission) as total_commissions')
            ->join('type_operations', 'type_operations.id = transactions.id_type_operation')
            ->groupBy('transactions.id_type_operation')
            ->get()->getResultArray();

        $gainsMap = [];
        foreach ($typeOperations as $type) {
            $gainsMap[$type['code']] = [
                'nom' => $type['nom'],
                'nb' => 0,
                'total_frais' => 0,
                'total_commissions' => 0
            ];
        }
        foreach ($gains as $g) {
            if (isset($gainsMap[$g['type_code']])) {
                $gainsMap[$g['type_code']]['nb'] = $g['nb_transactions'] ?? 0;
                $gainsMap[$g['type_code']]['total_frais'] = $g['total_frais'] ?? 0;
                $gainsMap[$g['type_code']]['total_commissions'] = $g['total_commissions'] ?? 0;
            }
        }

        $clients = $clientModel->orderBy('telephone', 'ASC')->findAll();

        return view('operator/dashboard', [
            'prefixes' => $prefixes,
            'baremes' => $baremes,
            'typeOperations' => $typeOperations,
            'operateurs' => $operateurs,
            'gains' => $gainsMap,
            'clients' => $clients,
        ]);
    }

    // ── 2. SITUATION DES GAINS ──────────────────────────────
    public function getSituationGain()
    {
        $db = \Config\Database::connect();

        $gainsMemeOp = $db->query("
            SELECT
                COUNT(t.id) AS nb_transferts,
                SUM(t.frais) AS total_frais,
                SUM(t.commission) AS total_commissions,
                SUM(t.frais) + SUM(t.commission) AS gain_total
            FROM transactions t
            JOIN type_operations top ON top.id = t.id_type_operation AND top.code = 'TRA'
            JOIN clients c_exp ON c_exp.id = t.client_id_expediteur
            JOIN clients c_dest ON c_dest.id = t.client_id_destinataire
            JOIN prefixes p_exp ON p_exp.prefixe = SUBSTR(c_exp.telephone, 1, 3)
            JOIN prefixes p_dest ON p_dest.prefixe = SUBSTR(c_dest.telephone, 1, 3)
            WHERE p_exp.id_operateur = p_dest.id_operateur
        ")->getRowArray();

        $gainsAutreOp = $db->query("
            SELECT
                COUNT(t.id) AS nb_transferts,
                SUM(t.frais) AS total_frais,
                SUM(t.commission) AS total_commissions,
                SUM(t.frais) + SUM(t.commission) AS gain_total
            FROM transactions t
            JOIN type_operations top ON top.id = t.id_type_operation AND top.code = 'TRA'
            JOIN clients c_exp ON c_exp.id = t.client_id_expediteur
            JOIN clients c_dest ON c_dest.id = t.client_id_destinataire
            JOIN prefixes p_exp ON p_exp.prefixe = SUBSTR(c_exp.telephone, 1, 3)
            JOIN prefixes p_dest ON p_dest.prefixe = SUBSTR(c_dest.telephone, 1, 3)
            WHERE p_exp.id_operateur != p_dest.id_operateur
        ")->getRowArray();

        $gainsRetrait = $db->query("
            SELECT
                COUNT(t.id) AS nb_retraits,
                SUM(t.frais) AS total_frais
            FROM transactions t
            JOIN type_operations top ON top.id = t.id_type_operation AND top.code = 'RET'
        ")->getRowArray();

        return view('situation_gain', [
            'gainsMemeOp' => $gainsMemeOp,
            'gainsAutreOp' => $gainsAutreOp,
            'gainsRetrait' => $gainsRetrait,
        ]);
    }

    // ── 3. SITUATION DES MONTANTS ENVOYÉS PER OPERATEUR ──────
    public function getSituationMontantOperateur()
    {
        $db = \Config\Database::connect();

        $rows = $db->query("
            SELECT
                o.nom AS operateur,
                COUNT(t.id) AS nb_transferts,
                SUM(t.montant) AS montant_total
            FROM transactions t
            JOIN type_operations top ON top.id = t.id_type_operation AND top.code = 'TRA'
            JOIN clients c_dest ON c_dest.id = t.client_id_destinataire
            JOIN prefixes p_dest ON p_dest.prefixe = SUBSTR(c_dest.telephone, 1, 3)
            JOIN operateurs o ON o.id = p_dest.id_operateur
            GROUP BY o.id, o.nom
            ORDER BY montant_total DESC
        ")->getResultArray();

        return view('situation_operateur', [
            'montants' => $rows
        ]);
    }

    // ── 4. HISTORIQUE ENRICHI OPERATEUR ──────────────────────
    public function getHistoriqueOperateur()
    {
        $dateDebut = $this->request->getGet('date_debut') ?? '';
        $dateFin = $this->request->getGet('date_fin') ?? '';
        $telClient = trim($this->request->getGet('telephone') ?? '');
        $typeCode = $this->request->getGet('type_code') ?? '';

        $historiqueModel = new HistoriqueModel();
        $history = $historiqueModel->getHistoriqueOperateur([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'telephone' => $telClient,
            'type_code' => $typeCode
        ]);

        return view('historique_operateur', [
            'history' => $history,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'telClient' => $telClient,
            'typeCode' => $typeCode,
            'operateurs' => (new OperateurModel())->findAll(),
            'typeOps' => (new TypeOperationModel())->findAll(),
        ]);
    }

    // ── 5. CRUD OPÉRATEURS ──────────────────────────────────
    public function addOperateur()
    {
        $nom = trim($this->request->getPost('nom') ?? '');
        $pct = (float) ($this->request->getPost('pourcentage_commission') ?? 0);

        if (!empty($nom)) {
            $operateurModel = new OperateurModel();
            try {
                $operateurModel->insert(['nom' => $nom, 'pourcentage_commission' => $pct]);
                session()->setFlashdata('success', "Opérateur '$nom' ajouté.");
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Erreur : cet opérateur existe déjà.');
            }
        }
        return redirect()->to(site_url('operator'));
    }

    public function editOperateur()
    {
        $id = $this->request->getPost('id');
        $nom = trim($this->request->getPost('nom') ?? '');
        $pct = (float) ($this->request->getPost('pourcentage_commission') ?? 0);

        if ($id && !empty($nom)) {
            $operateurModel = new OperateurModel();
            $operateurModel->update($id, ['nom' => $nom, 'pourcentage_commission' => $pct]);
            session()->setFlashdata('success', 'Opérateur mis à jour.');
        }
        return redirect()->to(site_url('operator'));
    }

    public function deleteOperateur($id)
    {
        $operateurModel = new OperateurModel();
        $operateurModel->delete($id);
        session()->setFlashdata('success', 'Opérateur supprimé.');
        return redirect()->to(site_url('operator'));
    }

    // ── 6. CRUD PRÉFIXES ─────────────────────────────────────
    public function addPrefix()
    {
        $prefix = trim($this->request->getPost('prefixe') ?? '');
        $idOp = $this->request->getPost('id_operateur');

        if (!empty($prefix)) {
            $prefixModel = new PrefixModel();
            try {
                $prefixModel->insert(['prefixe' => $prefix, 'id_operateur' => $idOp ?: null]);
                session()->setFlashdata('success', 'Préfixe ajouté avec succès.');
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Préfixe existe déjà.');
            }
        }
        return redirect()->to(site_url('operator'));
    }

    public function deletePrefix($id)
    {
        $prefixModel = new PrefixModel();
        $prefixModel->delete($id);
        session()->setFlashdata('success', 'Préfixe supprimé.');
        return redirect()->to(site_url('operator'));
    }

    // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
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
                'frais_pourcentage' => $pct, 
            ]);
            session()->setFlashdata('success', 'Barème ajouté.');
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
                'frais_pourcentage' => $pct,


            ]);
            session()->setFlashdata('success', 'Barème mis à jour.');
        }
        return redirect()->to(site_url('operator'));
    }

    public function deleteBareme($id)
    {
        $baremeModel = new BaremeFraisModel();
        $baremeModel->delete($id);
        session()->setFlashdata('success', 'Barème supprimé.');
        return redirect()->to(site_url('operator'));
    }
}
