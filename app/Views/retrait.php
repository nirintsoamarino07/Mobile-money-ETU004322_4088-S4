<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait Client – Mobile Money</title>
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
        main { max-width: 600px; margin: 40px auto; padding: 0 20px; }
        .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: .9rem; }
        .alert-error { background: #fee2e2; border-left: 4px solid #dc2626; color: #7f1d1d; }
        .form-card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 30px rgba(0,0,0,.05); border: 1px solid #e2e8f0; }
        .form-card h2 { font-size: 1.2rem; font-weight: 800; color: #0d3a8c; margin-bottom: 20px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        .client-mini-info { display: flex; justify-content: space-between; font-size: .88rem; color: #718096; background: #f7fafc; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; }
        label { display: block; font-size: .85rem; font-weight: 700; color: #4a5568; margin-bottom: 8px; }
        input[type="number"] { width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e0; border-radius: 10px; font-size: 1.1rem; outline: none; margin-bottom: 10px; }
        input[type="number"]:focus { border-color: #1a56db; }
        .frais-estim { font-size: .83rem; color: #718096; margin-bottom: 20px; font-style: italic; }
        button { width: 100%; padding: 13px; background: linear-gradient(135deg, #dc2626, #991b1b); color: #fff; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
        button:hover { opacity: .9; }
        .bareme-box { margin-top: 24px; background: #fdf2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 16px; font-size: .82rem; }
        .bareme-box title { font-weight: 700; display: block; margin-bottom: 8px; color: #991b1b; }
        .bareme-box table { width: 100%; border-collapse: collapse; }
        .bareme-box th, .bareme-box td { text-align: left; padding: 4px 6px; }
    </style>
</head>
<body>
<header>
    <h1>📱 Mobile Money</h1>
    <nav>
        <a href="<?= site_url('solde') ?>">Solde</a>
        <a href="<?= site_url('depot') ?>">Dépôt</a>
        <a href="<?= site_url('retrait') ?>" class="active">Retrait</a>
        <a href="<?= site_url('transfert') ?>">Transfert</a>
        <a href="<?= site_url('multi-transfert') ?>">Envoi multiple</a>
        <a href="<?= site_url('historique') ?>">Historique Client</a>
        <a class="logout-btn" href="<?= site_url('logout') ?>">Déconnexion</a>
    </nav>
</header>
<main>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="form-card">
        <h2>📤 Effectuer un Retrait</h2>
        <div class="client-mini-info">
            <span>Numéro : <?= esc($client['telephone']) ?></span>
            <span>Solde actuel : <?= number_format($client['solde'], 2, ',', ' ') ?> Ar</span>
        </div>

        <form action="<?= site_url('retrait') ?>" method="post">
            <?= csrf_field() ?>
            <label for="amount">Montant à retirer (Ar)</label>
            <input type="number" id="amount" name="amount" required min="1" step="any" placeholder="Ex : 50000" autofocus oninput="validerFrais()">
            <div class="frais-estim" id="frais-info">Saisissez un montant pour voir les frais applicables.</div>
            <button type="submit">Confirmer le Retrait</button>
        </form>

        <div class="bareme-box">
            <span style="font-weight: 700; color: #991b1b; display: block; margin-bottom: 6px;">Barème des frais de retrait</span>
            <table>
                <thead>
                    <tr style="border-bottom: 1px solid #fca5a5;">
                        <th style="padding: 4px;">Tranche</th>
                        <th style="padding: 4px;">Frais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="padding: 4px;">0 Ar - 10 000 Ar</td><td style="padding: 4px;">100 Ar</td></tr>
                    <tr><td style="padding: 4px;">10 001 Ar - 50 000 Ar</td><td style="padding: 4px;">500 Ar</td></tr>
                    <tr><td style="padding: 4px;">50 001 Ar - 100 000 Ar</td><td style="padding: 4px;">800 Ar</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
function validerFrais() {
    var mont = parseFloat(document.getElementById('amount').value);
    var info = document.getElementById('frais-info');
    if (isNaN(mont) || mont <= 0) {
        info.innerHTML = "Saisissez un montant pour voir les frais applicables.";
        return;
    }
    var frais = 0;
    if (mont <= 10000) {
        frais = 100;
    } else if (mont <= 50000) {
        frais = 500;
    } else if (mont <= 100000) {
        frais = 800;
    } else {
        frais = 0;
    }
    info.innerHTML = "Frais applicables : <strong>" + frais + " Ar</strong> (Total débité : <strong>" + (mont + frais) + " Ar</strong>)";
}
</script>
</body>
</html>
