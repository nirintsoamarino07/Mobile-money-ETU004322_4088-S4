1. Configuration du projet
Base de données
 Configurer la connexion à la base de données dans app/Config/Database.php.
 Vérifier que la connexion fonctionne correctement.
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
3. Gestion des opérations bancaires
A. Consulter le solde
Routes
    Route vers la page Solde.
Controller
    getSolde()
Model
    Fonction permettant de récupérer le solde du client connecté.
View
    solde.php
B. Effectuer un dépôt
Routes
    Route d'affichage du formulaire.
    Route de traitement du dépôt.
Controller
    showDepot()
    saveDepot()
Model
    Fonction d'insertion du dépôt.
    Mise à jour automatique du solde.
View
    depot.php
C. Effectuer un retrait
Routes
    Route d'affichage du formulaire.
    Route de traitement du retrait.
Controller
    showRetrait()
    saveRetrait()
Model
    Vérifier que le solde est suffisant.
    Enregistrer le retrait.
    Mettre à jour le solde.
View
    retrait.php
D. Effectuer un transfert
Routes
    Route d'affichage du formulaire.
    Route de traitement du transfert.
Controller
    showTransfert()
    saveTransfert()
Model
    Vérifier l'existence du destinataire.
    Vérifier le solde du client.
    Débiter l'expéditeur.
    Créditer le destinataire.
    Enregistrer l'opération.
View
    transfert.php
E. Consulter l'historique
Routes
    Route vers l'historique.
Controller
    getHistorique()
Model
    Fonction de récupération des opérations du client.
View
    historique.php
4. Gestion des Models
 findByTelephone()
 insertClient()
 getSolde()
 insertDepot()
 insertRetrait()
 insertTransfert()
 getHistorique()
5. Gestion des Sessions
 Créer la session après connexion.
 Vérifier que l'utilisateur est connecté avant chaque opération.
 Ajouter la déconnexion (logout).