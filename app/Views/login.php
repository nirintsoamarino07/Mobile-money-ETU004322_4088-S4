<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client – Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-auth.css') ?>">
</head>
<body>
<div class="card">
    <h1>📱 Mobile Money</h1>
    <p class="sub">Saisissez votre numéro de téléphone pour accéder à votre compte.</p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>
        <label for="telephone">Numéro de téléphone</label> 
        <label for="">Epargne</label> 
        <input type="text" id="telephone" name="telephone" required placeholder="Ex : 0321234567" autocomplete="tel">
        <button type="submit">Se connecter / Créer un compte</button>
    </form>

    <div class="info-box">
        <strong>Préfixes acceptés :</strong>
        <ul>
            <li>032, 037 → <strong>Orange</strong></li>
            <li>034, 038 → <strong>Yas</strong></li>
            <li>033 → <strong>Airtel</strong></li>
        </ul>
    </div>

    <div class="op-link">
        <a href="<?= site_url('operator') ?>">🔧 Accéder à l'espace opérateur</a>
    </div>
</div>
</body>
</html>
