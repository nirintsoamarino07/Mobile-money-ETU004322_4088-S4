<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Client - Simulateur Mobile Money</title>
</head>
<body>
    <h1>Connexion Client</h1>
    
    <div>
        <a href="<?= site_url('operator') ?>">Espace Opérateur</a>
    </div>

    <hr>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green; font-weight: bold;">[SUCCESS] <?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red; font-weight: bold;">[ERROR] <?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= site_url('client/login') ?>" method="post">
        <?= csrf_field() ?>
        <p>
            <label for="telephone">Numéro de téléphone :</label>
            <input type="text" id="telephone" name="telephone" required placeholder="ex: 0331234567">
        </p>
        <p>
            <button type="submit">Se connecter / Commencer</button>
        </p>
    </form>
</body>
</html>
