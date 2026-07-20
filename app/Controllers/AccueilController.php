<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AccueilController extends BaseController
{
    public function index()
    {
        // If client is logged in, redirect to dashboard
      $db = \Config\Database::connect();

if ($db) {
    echo "Connexion OK";
}
    }
}
