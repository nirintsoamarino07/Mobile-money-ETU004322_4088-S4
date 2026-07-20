<?php

namespace App\Controllers;

use App\Models\PrefixModel;
use App\Models\ClientModel;

class ClientAuthController extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('client_id')) {
            return redirect()->to(site_url('client/dashboard'));
        }

        return view('client/login');
    }

    public function doLogin()
    {
        $telephone = trim($this->request->getPost('telephone') ?? '');

        if (empty($telephone)) {
            session()->setFlashdata('error', 'Le numéro de téléphone est requis.');
            return redirect()->to(site_url('client/login'));
        }

        
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
            return redirect()->to(site_url('client/login'));
        }

     
        $clientModel = new ClientModel();
        $client = $clientModel->where('telephone', $telephone)->first();

        if (!$client) {
      
            $clientId = $clientModel->insert([
                'telephone' => $telephone,
                'solde' => 0.0
            ]);
            $client = $clientModel->find($clientId);
        }

   
        session()->set([
            'client_id' => $client['id'],
            'client_telephone' => $client['telephone']
        ]);

        session()->setFlashdata('success', 'Connexion réussie.');
        return redirect()->to(site_url('client/dashboard'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('client/login'));
    }
}
