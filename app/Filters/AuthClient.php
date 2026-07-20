<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filtre d'authentification pour les clients.
 * Redirige vers la page de connexion si l'utilisateur n'est pas connecté.
 */
class AuthClient implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('client_connecte')) {
            $session->setFlashdata('erreur', 'Veuillez vous connecter pour accéder à cette page.');
            return redirect()->to('/connexion');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête
    }
}
