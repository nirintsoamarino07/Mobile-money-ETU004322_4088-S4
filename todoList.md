VERSION 1
1. Configuration du projet
Base de données
Configurer la connexion à la base de données (Database.php).
Vérifier la connexion avec SQLite.
Créer les tables nécessaires (client, depot, retrait, transfert, historique, frais).
2. Authentification (Login automatique)
Fonctionnalités
    Permettre la connexion uniquement avec le numéro de téléphone.
    Si le numéro n'existe pas, créer automatiquement un nouveau client.
    Si le numéro existe déjà, connecter directement le client.
    Enregistrer l'identifiant du client dans la session.
Routes
    Route vers la page de connexion.
    Route pour traiter le formulaire de connexion.
Controller
    showLogin() : afficher le formulaire de connexion.
    login() :
    récupérer le numéro de téléphone ;
    vérifier son existence en base ;
    créer automatiquement le client si nécessaire ;
    ouvrir la session ;
    rediriger vers la page d'accueil.
Model
    findByTelephone()
    insertClient() (Query Builder)
View
    login.php
3. Consulter le solde
Fonctionnalités
    Afficher le solde du client connecté.
Routes
    Route vers la page Solde.
Controller
    getSolde()
    Model
    getSoldeClient()
View
    solde.php
4. Dépôt
Fonctionnalités
    Effectuer un dépôt.
    Mettre à jour le solde du client.
    Enregistrer l'opération dans l'historique.
Routes
    Route d'affichage du formulaire.
    Route d'enregistrement du dépôt.
Controller
    showDepot()
    saveDepot()
Model
    insertDepot()
    updateSolde()
View
    depot.php
5. Retrait
Fonctionnalités
    Vérifier le solde du client.
    Calculer les frais de retrait.
    Débiter le montant et les frais.
    Enregistrer l'opération.
Routes
    Route d'affichage du formulaire.
    Route d'enregistrement du retrait.
Controller
    showRetrait()
    saveRetrait()
Model
    insertRetrait()
    updateSolde()
    getFraisByMontant()
View
    retrait.php
6. Transfert
Fonctionnalités
    Envoyer un montant vers un autre client.
    Vérifier le solde.
    Calculer les frais de transfert.
    Débiter l'expéditeur.
    Créditer le destinataire.
    Enregistrer l'opération.
Routes
    Route d'affichage du formulaire.
    Route d'enregistrement du transfert.
Controller
    showTransfert()
    saveTransfert()
Model
    insertTransfert()
    updateSolde()
    getFraisByMontant()
View
    transfert.php
7. Historique
Fonctionnalités
    Afficher toutes les opérations du client.
    Routes
    Route vers l'historique.
Controller
    getHistorique()
Model
    getHistoriqueClient()
View
    historique.php

VERSION 2
1. Gestion des opérateurs
Fonctionnalités
Créer une table operateur.
Ajouter les opérateurs :
Yas (30%)
Airtel (50%)
Orange (20%)
Modifier la table prefixe en ajoutant id_operateur.
Associer chaque préfixe à son opérateur.
Préfixes
034 → Yas
038 → Yas
033 → Airtel
032 → Orange
037 → Orange
2. Détection automatique de l'opérateur
Fonctionnalités
    Identifier automatiquement l'opérateur du destinataire grâce au préfixe.
    Récupérer les informations de l'opérateur correspondant.
Controller
    Utiliser la détection dans la fonction saveTransfert().
Model
    getOperateurByPrefix()
3. Commission entre opérateurs
Fonctionnalités
    Vérifier si le transfert est effectué vers un autre opérateur.
    Récupérer le pourcentage de commission de l'opérateur destinataire.
    Calculer automatiquement la commission.
    Ajouter cette commission aux frais du transfert.
    Enregistrer la commission dans l'historique.
Controller
    Modifier saveTransfert() afin de calculer automatiquement la commission.
Model
    getOperateurByPrefix()
    getCommissionOperateur()
4. Option « Inclure les frais de retrait »
Fonctionnalités
    Ajouter une case à cocher :
    Inclure les frais de retrait
    Si elle est cochée :
    ajouter les frais de retrait au montant payé par l'expéditeur.
    Si elle n'est pas cochée :
    le destinataire paiera les frais lors du retrait.
    Si le transfert est vers un autre opérateur :
    ne pas appliquer les frais de retrait.
Routes
    Réutiliser la route du transfert.
Controller
    Modifier saveTransfert() pour gérer cette option.
Model
    getFraisRetrait()
View
    transfert.php
5. Envoi multiple
Fonctionnalités
    Envoyer un montant vers plusieurs numéros.
    Diviser automatiquement le montant entre tous les destinataires.
    Autoriser uniquement les destinataires appartenant au même opérateur.
    Vérifier le solde.
    Enregistrer chaque transfert.
Routes
    Route vers le formulaire.
    Route de traitement.
Controller
    showMultiTransfert()
    saveMultiTransfert()
Model
    insertMultiTransfert()
    updateSolde()
    getOperateurByPrefix()
View
    multiTransfert.php
6. Situation des gains
Fonctionnalités
    Créer une page permettant d'afficher :
    Même opérateur
    Nombre de transferts.
    Total des frais.
    Gain total.
    Autres opérateurs
    Nombre de transferts.
    Total des commissions.
    Gain total.
Routes
    Route vers la situation des gains.
Controller
    getSituationGain()
Model
    getSituationGain()
View
    situation_gain.php
7. Situation des montants envoyés
Fonctionnalités
    Afficher le montant total envoyé vers chaque opérateur :
    Yas
    Airtel
    Orange
Routes
    Route vers les statistiques.
Controller
    getSituationMontantOperateur()
Model
    getSituationMontantOperateur()
View
    situation_operateur.php
8. Historique opérateur
Fonctionnalités
    Consulter toutes les opérations.
    Filtrer par date.
    Filtrer par client.
    Filtrer par opérateur.
    Afficher les frais et les commissions.
Routes
    Route vers l'historique opérateur.
Controller
    getHistoriqueOperateur()
Model
    getHistoriqueOperateur()
View
    historique_operateur.php 

    pqgede pourcentqge chacun a son pourcentage pt d4esparne base koto ex pr 5  trnafert le 5 transact dans leparge et reste dans le sold 