<?php

namespace App\Controllers;

use App\Models\PrefixModel;
use App\Models\ClientModel;

class ClientAuthController extends BaseController
{
    /**
     * Renders login.php view
     */
    public function showLogin()
    {
        if (session()->has('client_id')) {
            return redirect()->to(site_url('solde'));
        }
        return view('login');
    }

    /**
     * Handles authentication logic
     */
    public function login()
    {
        $telephone = trim($this->request->getPost('telephone') ?? '');

        if (empty($telephone)) {
            session()->setFlashdata('error', 'Le numéro de téléphone est requis.');
            return redirect()->to(site_url('/'));
        }

        // Validate prefix
        $prefixModel = new PrefixModel();
        $prefixes = $prefixModel->findAll();
        $validPrefixes = array_column($prefixes, 'prefixe');

        $isValid = false;
        foreach ($validPrefixes as $prefix) {
            if (strpos($telephone, $prefix) === 0) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            session()->setFlashdata('error', 'Numéro invalide. Cet opérateur n\'accepte pas ce préfixe.');
            return redirect()->to(site_url('/'));
        }

        $clientModel = new ClientModel();
        $client = $clientModel->findByTelephone($telephone);

        if (!$client) {
            // Auto create client with 0 solde
            $clientId = $clientModel->insertClient($telephone);
            $client = $clientModel->find($clientId);
        }

        session()->set([
            'client_id' => $client['id'],
            'client_telephone' => $client['telephone']
        ]);

        session()->setFlashdata('success', 'Connexion réussie.');
        return redirect()->to(site_url('solde'));
    }

    /**
     * Handles logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('/'));
    }
}
