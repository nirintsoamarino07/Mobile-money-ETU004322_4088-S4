<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixModel;
use App\Models\OperateurModel;
use App\Models\FraisModel;
use App\Models\DepotModel;
use App\Models\RetraitModel;
use App\Models\TransfertModel;
use App\Models\HistoriqueModel;

class ClientDashboardController extends BaseController
{
    private function checkAuth()
    {
        if (!session()->has('client_id')) {
            return false;
        }
        return true;
    }

    private function getLoggedInClient()
    {
        $clientId = session()->get('client_id');
        $clientModel = new ClientModel();
        return $clientModel->find($clientId);
    }

    // ── 1. INDEX (Redirects to getSolde) ─────────────────────
    public function index()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }
        return redirect()->to(site_url('solde'));
    }

    // ── 2. CONSULTER LE SOLDE ────────────────────────────────
    public function getSolde()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $client = $this->getLoggedInClient();
        if (!$client) {
            session()->destroy();
            return redirect()->to(site_url('/'));
        }

        return view('solde', [
            'client' => $client
        ]);
    }

    // ── 3. DEPOT ─────────────────────────────────────────────
    public function showDepot()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $client = $this->getLoggedInClient();
        return view('depot', [
            'client' => $client
        ]);
    }

    public function saveDepot()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $clientId = session()->get('client_id');
        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            session()->setFlashdata('error', 'Le montant du dépôt doit être supérieur à 0.');
            return redirect()->to(site_url('depot'));
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            return redirect()->to(site_url('/'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $nouveauSolde = $client['solde'] + $amount;
        $clientModel->updateSolde($clientId, $nouveauSolde);

        $depotModel = new DepotModel();
        $depotModel->insertDepot($clientId, $amount, 0.0, $nouveauSolde);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec du dépôt en raison d\'une erreur système.');
        } else {
            session()->setFlashdata('success', 'Dépôt de ' . number_format($amount, 2, ',', ' ') . ' Ar effectué avec succès.');
        }

        return redirect()->to(site_url('solde'));
    }

    // ── 4. RETRAIT ───────────────────────────────────────────
    public function showRetrait()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $client = $this->getLoggedInClient();
        return view('retrait', [
            'client' => $client
        ]);
    }

    public function saveRetrait()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $clientId = session()->get('client_id');
        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            session()->setFlashdata('error', 'Le montant du retrait doit être supérieur à 0.');
            return redirect()->to(site_url('retrait'));
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            return redirect()->to(site_url('/'));
        }

        $fraisModel = new FraisModel();
        $frais = $fraisModel->getFraisByMontant('RET', $amount);
        $totalDebite = $amount + $frais;

        if ($client['solde'] < $totalDebite) {
            session()->setFlashdata('error', 'Solde insuffisant pour ce retrait. Requis: ' . number_format($totalDebite, 2, ',', ' ') . ' Ar (Montant: ' . number_format($amount, 2, ',', ' ') . ' Ar + Frais: ' . number_format($frais, 2, ',', ' ') . ' Ar). Solde actuel: ' . number_format($client['solde'], 2, ',', ' ') . ' Ar.');
            return redirect()->to(site_url('retrait'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $nouveauSolde = $client['solde'] - $totalDebite;
        $clientModel->updateSolde($clientId, $nouveauSolde);

        $retraitModel = new RetraitModel();
        $retraitModel->insertRetrait($clientId, $amount, $frais, $nouveauSolde);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec du retrait en raison d\'une erreur de transaction.');
        } else {
            session()->setFlashdata('success', 'Retrait de ' . number_format($amount, 2, ',', ' ') . ' Ar effectué avec succès (Frais prélevés: ' . number_format($frais, 2, ',', ' ') . ' Ar).');
        }

        return redirect()->to(site_url('solde'));
    }

    // ── 5. TRANSFERT ─────────────────────────────────────────
    public function showTransfert()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $client = $this->getLoggedInClient();
        return view('transfert', [
            'client' => $client
        ]);
    }

    public function saveTransfert()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $clientId = session()->get('client_id');
        $destTelephone = trim($this->request->getPost('telephone_dest') ?? '');
        $amount = (float) $this->request->getPost('amount');
        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') ? true : false;

        if (empty($destTelephone)) {
            session()->setFlashdata('error', 'Le numéro du destinataire est requis.');
            return redirect()->to(site_url('transfert'));
        }

        if ($amount <= 0) {
            session()->setFlashdata('error', 'Le montant du transfert doit être supérieur à 0.');
            return redirect()->to(site_url('transfert'));
        }

        $clientModel = new ClientModel();
        $sender = $clientModel->find($clientId);

        if (!$sender) {
            return redirect()->to(site_url('/'));
        }

        if ($sender['telephone'] === $destTelephone) {
            session()->setFlashdata('error', 'Vous ne pouvez pas effectuer un transfert vers votre propre numéro.');
            return redirect()->to(site_url('transfert'));
        }

        // Detect operator of destinataire via Prefix
        $destPrefix = substr($destTelephone, 0, 3);
        $prefixModel = new PrefixModel();
        $prefixExists = $prefixModel->where('prefixe', $destPrefix)->first();

        if (!$prefixExists) {
            session()->setFlashdata('error', 'Numéro destinataire invalide. Le préfixe ' . $destPrefix . ' n\'est pas supporté.');
            return redirect()->to(site_url('transfert'));
        }

        // Get sender operator
        $senderPrefix = substr($sender['telephone'], 0, 3);
        $operateurModel = new OperateurModel();
        $senderOp = $operateurModel->getOperateurByPrefix($senderPrefix);

        // Get destinataire operator
        $destOp = $operateurModel->getOperateurByPrefix($destPrefix);

        if (!$senderOp || !$destOp) {
            session()->setFlashdata('error', 'Erreur lors de la détection de l\'opérateur.');
            return redirect()->to(site_url('transfert'));
        }

        // Calculate transfer fees
        $fraisModel = new FraisModel();
        $fraisTransfert = $fraisModel->getFraisByMontant('TRA', $amount);

        $commission = 0.0;
        $fraisRetraitAAjouter = 0.0;

        if ($senderOp['id'] === $destOp['id']) {
            // Same operator
            if ($inclureFraisRetrait) {
                // If checked, add withdrawal fees to the amount paid by the sender
                $retraitModel = new RetraitModel();
                $fraisRetraitAAjouter = $retraitModel->getFraisRetrait($amount);
            }
        } else {
            // Different operator: calculate commission based on recipient operator percentage
            $pourcentage = (float) $destOp['pourcentage_commission'];
            $commission = ($fraisTransfert * $pourcentage) / 100;
            // No withdrawal fees added if transfer is to another operator
            $inclureFraisRetrait = false;
        }

        $totalFrais = $fraisTransfert + $fraisRetraitAAjouter;
        $totalDebite = $amount + $totalFrais + $commission;

        if ($sender['solde'] < $totalDebite) {
            session()->setFlashdata('error', 'Solde insuffisant pour ce transfert. Total débité estimé: ' . number_format($totalDebite, 2, ',', ' ') . ' Ar. Solde actuel: ' . number_format($sender['solde'], 2, ',', ' ') . ' Ar.');
            return redirect()->to(site_url('transfert'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $recipient = $clientModel->findByTelephone($destTelephone);
        if (!$recipient) {
            // Auto create client with 0 solde
            $recipientId = $clientModel->insertClient($destTelephone);
            $recipient = $clientModel->find($recipientId);
        } else {
            $recipientId = $recipient['id'];
        }

        $nouveauSoldeSender = $sender['solde'] - $totalDebite;
        // The recipient receives the amount. If the sender included withdrawal fees, recipient shouldn't pay them, but their account is credited by the clean amount.
        $nouveauSoldeRecipient = $recipient['solde'] + $amount;

        $clientModel->updateSolde($clientId, $nouveauSoldeSender);
        $clientModel->updateSolde($recipientId, $nouveauSoldeRecipient);

        $transfertModel = new TransfertModel();
        // saveTransfert stores total transaction fees (transfer fees + withdrawal fees) and commission separately
        $transfertModel->insertTransfert($clientId, $recipientId, $amount, $totalFrais, $commission, $nouveauSoldeSender);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec du transfert.');
        } else {
            session()->setFlashdata('success', 'Transfert de ' . number_format($amount, 2, ',', ' ') . ' Ar effectué avec succès vers ' . $destTelephone . ' (Opérateur: ' . $destOp['nom'] . ').');
        }

        return redirect()->to(site_url('solde'));
    }

    // ── 6. ENVOI MULTIPLE ────────────────────────────────────
    public function showMultiTransfert()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $client = $this->getLoggedInClient();
        return view('multiTransfert', [
            'client' => $client
        ]);
    }

    public function saveMultiTransfert()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $clientId = session()->get('client_id');
        $montantTotal = (float) $this->request->getPost('montant_total');
        $destinatairesText = trim($this->request->getPost('destinataires') ?? '');

        if ($montantTotal <= 0) {
            session()->setFlashdata('error', 'Le montant total doit être supérieur à 0.');
            return redirect()->to(site_url('multi-transfert'));
        }

        if (empty($destinatairesText)) {
            session()->setFlashdata('error', 'La liste des destinataires est requise.');
            return redirect()->to(site_url('multi-transfert'));
        }

        // Parse recipients (comma-separated or line-breaks)
        $destinataires = preg_split('/[\r\n,]+/', $destinatairesText);
        $destinataires = array_filter(array_map('trim', $destinataires));
        $destinataires = array_unique($destinataires);

        $count = count($destinataires);
        if ($count === 0) {
            session()->setFlashdata('error', 'Aucun destinataire valide spécifié.');
            return redirect()->to(site_url('multi-transfert'));
        }

        $clientModel = new ClientModel();
        $sender = $clientModel->find($clientId);

        if (!$sender) {
            return redirect()->to(site_url('/'));
        }

        $senderPrefix = substr($sender['telephone'], 0, 3);
        $operateurModel = new OperateurModel();
        $senderOp = $operateurModel->getOperateurByPrefix($senderPrefix);

        if (!$senderOp) {
            session()->setFlashdata('error', 'Impossible de détecter votre opérateur.');
            return redirect()->to(site_url('multi-transfert'));
        }

        // Validate that all recipients belong to the same operator
        foreach ($destinataires as $destVal) {
            if ($destVal === $sender['telephone']) {
                session()->setFlashdata('error', 'Vous ne pouvez pas vous inclure dans la liste des destinataires.');
                return redirect()->to(site_url('multi-transfert'));
            }

            $destPrefix = substr($destVal, 0, 3);
            $destOp = $operateurModel->getOperateurByPrefix($destPrefix);

            if (!$destOp) {
                session()->setFlashdata('error', 'Le numéro ' . $destVal . ' a un préfixe invalide.');
                return redirect()->to(site_url('multi-transfert'));
            }

            if ($destOp['id'] !== $senderOp['id']) {
                session()->setFlashdata('error', 'L\'envoi multiple n\'est possible que vers des numéros du même opérateur (' . $senderOp['nom'] . '). Le numéro ' . $destVal . ' appartient à l\'opérateur ' . $destOp['nom'] . '.');
                return redirect()->to(site_url('multi-transfert'));
            }
        }

        // Divide the total amount equally
        $amountPerRecipient = $montantTotal / $count;

        // Calculate fees for each sub-transfer
        $fraisModel = new FraisModel();
        $fraisPerTransfert = $fraisModel->getFraisByMontant('TRA', $amountPerRecipient);
        $totalCostPerTransfert = $amountPerRecipient + $fraisPerTransfert;

        $totalDebite = $totalCostPerTransfert * $count;

        if ($sender['solde'] < $totalDebite) {
            session()->setFlashdata('error', 'Solde insuffisant pour cet envoi multiple. Montant par destinataire: ' . number_format($amountPerRecipient, 2, ',', ' ') . ' Ar + Frais: ' . number_format($fraisPerTransfert, 2, ',', ' ') . ' Ar. Total débité estimé: ' . number_format($totalDebite, 2, ',', ' ') . ' Ar. Solde actuel: ' . number_format($sender['solde'], 2, ',', ' ') . ' Ar.');
            return redirect()->to(site_url('multi-transfert'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $soldeCourant = $sender['solde'];
        $transfertModel = new TransfertModel();

        foreach ($destinataires as $destVal) {
            $recipient = $clientModel->findByTelephone($destVal);
            if (!$recipient) {
                $recipientId = $clientModel->insertClient($destVal);
                $recipient = $clientModel->find($recipientId);
            } else {
                $recipientId = $recipient['id'];
            }

            $soldeCourant -= $totalCostPerTransfert;
            $nouveauSoldeRecipient = $recipient['solde'] + $amountPerRecipient;

            $clientModel->updateSolde($recipientId, $nouveauSoldeRecipient);
            $transfertModel->insertMultiTransfert($clientId, $recipientId, $amountPerRecipient, $fraisPerTransfert, $soldeCourant);
        }

        $clientModel->updateSolde($clientId, $soldeCourant);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Échec de l\'envoi multiple.');
        } else {
            session()->setFlashdata('success', 'Envoi multiple effectué avec succès vers ' . $count . ' destinataires.');
        }

        return redirect()->to(site_url('solde'));
    }

    // ── 7. HISTORIQUE ────────────────────────────────────────
    public function getHistorique()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(site_url('/'));
        }

        $client = $this->getLoggedInClient();
        $historiqueModel = new HistoriqueModel();
        $history = $historiqueModel->getHistoriqueClient($client['id']);

        return view('historique', [
            'client' => $client,
            'history' => $history
        ]);
    }
}
