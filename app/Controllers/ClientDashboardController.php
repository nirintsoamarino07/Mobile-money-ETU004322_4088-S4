<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;
use App\Models\TransactionModel;

class ClientDashboardController extends BaseController
{
    private function checkAuth()
    {
        if (!session()->has('client_id')) {
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('client/login'));
        }

        $clientId = session()->get('client_id');

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            session()->destroy();
            return redirect()->to(site_url('client/login'));
        }

        // Fetch transaction history
        $db = \Config\Database::connect();
        $history = $db->table('transactions')
            ->select('transactions.*, type_operations.nom as type_nom, type_operations.code as type_code, c_exp.telephone as expediteur_tel, c_dest.telephone as destinataire_tel')
            ->join('type_operations', 'type_operations.id = transactions.id_type_operation')
            ->join('clients as c_exp', 'c_exp.id = transactions.client_id_expediteur', 'left')
            ->join('clients as c_dest', 'c_dest.id = transactions.client_id_destinataire', 'left')
            ->where('transactions.client_id_expediteur', $clientId)
            ->orWhere('transactions.client_id_destinataire', $clientId)
            ->orderBy('transactions.date_transaction', 'DESC')
            ->get()
            ->getResultArray();

        return view('client/dashboard', [
            'client' => $client,
            'history' => $history
        ]);
    }

    // Calcul des frais selon le bareme
    private function getFees($typeCode, $amount)
    {
        $db = \Config\Database::connect();
        
        // Obtenir l'ID du type d'operation
        $typeOp = $db->table('type_operations')
            ->where('code', $typeCode)
            ->get()
            ->getRowArray();

        if (!$typeOp) {
            return 0.0;
        }

        // Trouver la tranche de bareme correspondante
        $bareme = $db->table('bareme_frais')
            ->where('id_type_operation', $typeOp['id'])
            ->where('montant_min <=', $amount)
            ->where('montant_max >=', $amount)
            ->get()
            ->getRowArray();

        if (!$bareme) {
            return 0.0;
        }

        $frais = $bareme['frais_fixe'] + ($amount * $bareme['frais_pourcentage'] / 100);
        return (float) $frais;
    }

    // 1. DEPOT
    public function deposit()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('client/login'));
        }

        $clientId = session()->get('client_id');
        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            session()->setFlashdata('error', 'Le montant du dépôt doit être supérieur à 0.');
            return redirect()->to(site_url('client/dashboard'));
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            return redirect()->to(site_url('client/login'));
        }

        // Recuperer le type d'operation (DEP)
        $db = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'DEP')->get()->getRowArray();

        // Calcul des frais (normalement 0)
        $frais = $this->getFees('DEP', $amount);

        // Effectuer l'operation en base
        $db->transStart();
        
        // Mettre a jour le solde (dépôt augmente le solde)
        $nouveauSolde = $client['solde'] + $amount;
        $clientModel->update($clientId, ['solde' => $nouveauSolde]);

        // Enregistrer la transaction
        $transactionModel = new TransactionModel();
        $transactionModel->insert([
            'id_type_operation' => $typeOp['id'],
            'client_id_expediteur' => null,
            'client_id_destinataire' => $clientId,
            'montant' => $amount,
            'frais' => $frais
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec du dépôt en raison d\'une erreur de transaction.');
        } else {
            session()->setFlashdata('success', 'Dépôt de ' . number_format($amount, 2, ',', ' ') . ' Ar effectué avec succès.');
        }

        return redirect()->to(site_url('client/dashboard'));
    }

    // 2. RETRAIT
    public function withdraw()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('client/login'));
        }

        $clientId = session()->get('client_id');
        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            session()->setFlashdata('error', 'Le montant du retrait doit être supérieur à 0.');
            return redirect()->to(site_url('client/dashboard'));
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            return redirect()->to(site_url('client/login'));
        }

        // Calcul des frais pour retrait
        $frais = $this->getFees('RET', $amount);
        $totalDebite = $amount + $frais;

        if ($client['solde'] < $totalDebite) {
            session()->setFlashdata('error', 'Solde insuffisant pour ce retrait. Requis: ' . number_format($amount, 2, ',', ' ') . ' Ar + ' . number_format($frais, 2, ',', ' ') . ' Ar de frais (Total: ' . number_format($totalDebite, 2, ',', ' ') . ' Ar). Solde actuel: ' . number_format($client['solde'], 2, ',', ' ') . ' Ar.');
            return redirect()->to(site_url('client/dashboard'));
        }

        // Effectuer le retrait
        $db = \Config\Database::connect();
        $typeOp = $db->table('type_operations')->where('code', 'RET')->get()->getRowArray();

        $db->transStart();

        $nouveauSolde = $client['solde'] - $totalDebite;
        $clientModel->update($clientId, ['solde' => $nouveauSolde]);

        // Enregistrer la transaction
        $transactionModel = new TransactionModel();
        $transactionModel->insert([
            'id_type_operation' => $typeOp['id'],
            'client_id_expediteur' => $clientId,
            'client_id_destinataire' => null,
            'montant' => $amount,
            'frais' => $frais
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec du retrait en raison d\'une erreur de transaction.');
        } else {
            session()->setFlashdata('success', 'Retrait de ' . number_format($amount, 2, ',', ' ') . ' Ar effectué avec succès (Frais prélevés: ' . number_format($frais, 2, ',', ' ') . ' Ar).');
        }

        return redirect()->to(site_url('client/dashboard'));
    }

    // 3. TRANSFERT
    public function transfer()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('client/login'));
        }

        $clientId = session()->get('client_id');
        $destTelephone = trim($this->request->getPost('telephone_dest') ?? '');
        $amount = (float) $this->request->getPost('amount');

        if (empty($destTelephone)) {
            session()->setFlashdata('error', 'Le numéro du destinataire est requis.');
            return redirect()->to(site_url('client/dashboard'));
        }

        if ($amount <= 0) {
            session()->setFlashdata('error', 'Le montant du transfert doit être supérieur à 0.');
            return redirect()->to(site_url('client/dashboard'));
        }

        $clientModel = new ClientModel();
        $sender = $clientModel->find($clientId);

        if (!$sender) {
            return redirect()->to(site_url('client/login'));
        }

        if ($sender['telephone'] === $destTelephone) {
            session()->setFlashdata('error', 'Vous ne pouvez pas effectuer un transfert vers votre propre numéro.');
            return redirect()->to(site_url('client/dashboard'));
        }

        // Valider le prefixe du destinataire
        $prefixModel = new PrefixModel();
        $prefixes = $prefixModel->findAll();
        $validPrefixes = array_column($prefixes, 'prefixe');

        $isValidDest = false;
        foreach ($validPrefixes as $prefix) {
            if (strpos($destTelephone, $prefix) === 0) {
                $isValidDest = true;
                break;
            }
        }

        if (!$isValidDest) {
            session()->setFlashdata('error', 'Numéro destinataire invalide. Le préfixe n\'est pas accepté par cet opérateur.');
            return redirect()->to(site_url('client/dashboard'));
        }

        // Obtenir ou créer le destinataire
        $recipient = $clientModel->where('telephone', $destTelephone)->first();
        $db = \Config\Database::connect();

        $db->transStart();

        if (!$recipient) {
            // Création automatique du destinataire avec solde = 0.0
            $recipientId = $clientModel->insert([
                'telephone' => $destTelephone,
                'solde' => 0.0
            ]);
            $recipient = $clientModel->find($recipientId);
        } else {
            $recipientId = $recipient['id'];
        }

        // Calcul des frais
        $frais = $this->getFees('TRA', $amount);
        $totalDebite = $amount + $frais;

        if ($sender['solde'] < $totalDebite) {
            $db->transRollback();
            session()->setFlashdata('error', 'Solde insuffisant pour ce transfert. Requis: ' . number_format($amount, 2, ',', ' ') . ' Ar + ' . number_format($frais, 2, ',', ' ') . ' Ar de frais (Total: ' . number_format($totalDebite, 2, ',', ' ') . ' Ar). Solde actuel: ' . number_format($sender['solde'], 2, ',', ' ') . ' Ar.');
            return redirect()->to(site_url('client/dashboard'));
        }

        // Mettre a jour les soldes
        $clientModel->update($clientId, ['solde' => $sender['solde'] - $totalDebite]);
        $clientModel->update($recipientId, ['solde' => $recipient['solde'] + $amount]);

        // Enregistrer la transaction
        $typeOp = $db->table('type_operations')->where('code', 'TRA')->get()->getRowArray();
        $transactionModel = new TransactionModel();
        $transactionModel->insert([
            'id_type_operation' => $typeOp['id'],
            'client_id_expediteur' => $clientId,
            'client_id_destinataire' => $recipientId,
            'montant' => $amount,
            'frais' => $frais
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec du transfert en raison d\'une erreur de transaction.');
        } else {
            session()->setFlashdata('success', 'Transfert de ' . number_format($amount, 2, ',', ' ') . ' Ar effectué avec succès vers ' . $destTelephone . ' (Frais: ' . number_format($frais, 2, ',', ' ') . ' Ar).');
        }

        return redirect()->to(site_url('client/dashboard'));
    }
}
