<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Solde – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; color: #1a202c; min-height: 100vh; }
        header { background: linear-gradient(135deg, #1a56db, #0d3a8c); color: #fff; padding: 16px 28px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        header h1 { font-size: 1.25rem; font-weight: 800; display: flex; align-items: center; gap: 8px; }
        header nav { display: flex; gap: 20px; }
        header nav a { color: rgba(255,255,255,.8); text-decoration: none; font-size: .92rem; font-weight: 600; transition: color .2s; }
        header nav a:hover { color: #fff; }
        header nav a.active { color: #fff; border-bottom: 2px solid #fff; padding-bottom: 2px; }
        .logout-btn { background: rgba(255,255,255,.15); padding: 6px 14px; border-radius: 6px; }
        .logout-btn:hover { background: rgba(255,255,255,.25); }
        main { max-width: 700px; margin: 40px auto; padding: 0 20px; }
        .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: .9rem; line-height: 1.4; }
        .alert-success { background: #d1fae5; border-left: 4px solid #059669; color: #065f46; }
        .alert-error { background: #fee2e2; border-left: 4px solid #dc2626; color: #7f1d1d; }
        .solde-card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 30px rgba(0,0,0,.05); border: 1px solid #e2e8f0; text-align: center; }
        .solde-card h2 { font-size: .88rem; text-transform: uppercase; color: #6b7280; letter-spacing: .05em; margin-bottom: 12px; }
        .solde-val { font-size: 2.8rem; font-weight: 800; color: #0d3a8c; margin-bottom: 24px; }
        .client-info { border-top: 1px solid #edf2f7; padding-top: 24px; display: flex; justify-content: space-around; font-size: .95rem; }
        .info-item { display: flex; flex-direction: column; gap: 4px; }
        .info-item span.label { color: #718096; font-size: .82rem; font-weight: 600; text-transform: uppercase; }
        .info-item span.val { font-weight: 700; color: #2d3748; }
        .actions-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 24px; }
        .action-link { display: block; border-radius: 12px; padding: 16px; text-align: center; border: 2px dashed #cbd5e0; color: #4a5568; text-decoration: none; font-weight: 700; font-size: .92rem; transition: all .2s; }
        .action-link:hover { border-color: #1a56db; color: #1a56db; background: rgba(26,86,219,.02); }
    </style>
</head>
<body>
<header>
    <h1>📱 Mobile Money</h1>
    <nav>
        <a href="<?= site_url('solde') ?>" class="active">Solde</a>
        <a href="<?= site_url('depot') ?>">Dépôt</a>
        <a href="<?= site_url('retrait') ?>">Retrait</a>
        <a href="<?= site_url('transfert') ?>">Transfert</a>
        <a href="<?= site_url('multi-transfert') ?>">Envoi multiple</a>
        <a href="<?= site_url('historique') ?>">Historique Client</a>
        <a class="logout-btn" href="<?= site_url('logout') ?>">Déconnexion</a>
    </nav>
</header>
<main>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="solde-card">
        <h2>Votre Solde Actuel</h2>
        <div class="solde-val"><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</div>

        <div class="client-info">
            <div class="info-item">
                <span class="label">Identifiant / Client</span>
                <span class="val">Client #<?= $client['id'] ?></span>
            </div>
            <div class="info-item">
                <span class="label">Numéro</span>
                <span class="val"><?= esc($client['telephone']) ?></span>
            </div>
        </div>
    </div>

    <div class="actions-grid">
        <a class="action-link" href="<?= site_url('depot') ?>">📥 Faire un Dépôt</a>
        <a class="action-link" href="<?= site_url('retrait') ?>">📤 Faire un Retrait</a>
        <a class="action-link" href="<?= site_url('transfert') ?>">💸 Faire un Transfert</a>
        <a class="action-link" href="<?= site_url('multi-transfert') ?>">👥 Envoi multiple destinataires</a>
    </div>
</main>
</body>
</html>
