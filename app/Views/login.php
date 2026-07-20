<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #1a56db 0%, #0d3a8c 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #fff; border-radius: 20px; padding: 44px 40px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .card h1 { font-size: 1.6rem; font-weight: 800; color: #1a56db; text-align: center; margin-bottom: 6px; }
        .card p.sub { text-align: center; color: #6b7280; font-size: .9rem; margin-bottom: 30px; }
        .alert { padding: 11px 16px; border-radius: 8px; margin-bottom: 18px; font-size: .88rem; }
        .alert-success { background:#d1fae5; border-left:4px solid #059669; color:#065f46; }
        .alert-error   { background:#fee2e2; border-left:4px solid #dc2626; color:#7f1d1d; }
        label { display: block; font-size: .83rem; font-weight: 600; color: #374151; margin-bottom: 7px; }
        input[type="text"] { width:100%; padding: 12px 16px; border: 1.5px solid #d1d5db; border-radius: 10px; font-size: 1rem; outline: none; transition: border-color .2s; }
        input[type="text"]:focus { border-color: #1a56db; box-shadow: 0 0 0 3px rgba(26,86,219,.15); }
        button { margin-top: 20px; width: 100%; padding: 13px; background: linear-gradient(135deg, #1a56db, #0d3a8c); color: #fff; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
        button:hover { opacity: .9; }
        .op-link { text-align: center; margin-top: 20px; font-size: .85rem; }
        .op-link a { color: #1a56db; text-decoration: none; font-weight: 600; }
        .op-link a:hover { text-decoration: underline; }
        .info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; font-size:.82rem; color:#1e40af; margin-top:20px; }
        .info-box ul { margin-left: 16px; line-height: 1.8; }
    </style>
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
