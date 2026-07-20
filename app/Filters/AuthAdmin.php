<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filtre d'authentification pour les administrateurs.
 * Redirige vers la page de connexion admin si l'admin n'est pas connecté.
 */
class AuthAdmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('admin_connecte')) {
            $session->setFlashdata('erreur', 'Accès refusé. Veuillez vous connecter en tant qu\'administrateur.');
            return redirect()->to(site_url('admin/connexion'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête
    }
}
