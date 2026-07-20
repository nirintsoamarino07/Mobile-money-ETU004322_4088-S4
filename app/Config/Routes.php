<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('ClientAuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);

// Home Redirect
$routes->get('/', function() {
    return redirect()->to(site_url('client/login'));
});

// Operator routes
$routes->get('operator', 'OperatorController::index');
$routes->post('operator/prefix/add', 'OperatorController::addPrefix');
$routes->get('operator/prefix/delete/(:num)', 'OperatorController::deletePrefix/$1');
$routes->post('operator/bareme/add', 'OperatorController::addBareme');
$routes->post('operator/bareme/edit', 'OperatorController::editBareme');
$routes->get('operator/bareme/delete/(:num)', 'OperatorController::deleteBareme/$1');