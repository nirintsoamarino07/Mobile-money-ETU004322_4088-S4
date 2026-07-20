<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('ClientAuthController');
$routes->setDefaultMethod('showLogin');
$routes->setTranslateURIDashes(false);

// ── Authentication ──
$routes->get('/', 'ClientAuthController::showLogin');
$routes->post('login', 'ClientAuthController::login');
$routes->get('logout', 'ClientAuthController::logout');

// ── Client routes ──
$routes->get('solde', 'ClientDashboardController::getSolde');

$routes->get('depot', 'ClientDashboardController::showDepot');
$routes->post('depot', 'ClientDashboardController::saveDepot');

$routes->get('retrait', 'ClientDashboardController::showRetrait');
$routes->post('retrait', 'ClientDashboardController::saveRetrait');

$routes->get('transfert', 'ClientDashboardController::showTransfert');
$routes->post('transfert', 'ClientDashboardController::saveTransfert');

$routes->get('multi-transfert', 'ClientDashboardController::showMultiTransfert');
$routes->post('multi-transfert', 'ClientDashboardController::saveMultiTransfert');

$routes->get('historique', 'ClientDashboardController::getHistorique');

// Redirect for dashboard
$routes->get('client/dashboard', 'ClientDashboardController::index');


// ── Operator routes ──
$routes->get('operator', 'OperatorController::index');
$routes->get('operator/gains', 'OperatorController::getSituationGain');
$routes->get('operator/montants', 'OperatorController::getSituationMontantOperateur');
$routes->get('operator/historique', 'OperatorController::getHistoriqueOperateur');

// CRUD Opérateurs
$routes->post('operator/operateur/add', 'OperatorController::addOperateur');
$routes->post('operator/operateur/edit', 'OperatorController::editOperateur');
$routes->get('operator/operateur/delete/(:num)', 'OperatorController::deleteOperateur/$1');

// CRUD Préfixes
$routes->post('operator/prefix/add', 'OperatorController::addPrefix');
$routes->get('operator/prefix/delete/(:num)', 'OperatorController::deletePrefix/$1');

// CRUD Barèmes
$routes->post('operator/bareme/add', 'OperatorController::addBareme');
$routes->post('operator/bareme/edit', 'OperatorController::editBareme');
$routes->get('operator/bareme/delete/(:num)', 'OperatorController::deleteBareme/$1');
