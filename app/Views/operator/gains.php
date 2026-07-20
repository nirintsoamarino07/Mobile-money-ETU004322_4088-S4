<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; color: #1a202c; }
        header { background: linear-gradient(135deg, #0f172a, #1e3a5f); color: #fff; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.1rem; font-weight: 700; }
        header a { color: rgba(255,255,255,.8); text-decoration: none; margin-left: 16px; font-size: .88rem; }
        header a:hover { color: #fff; }
        main { padding: 28px; max-width: 1000px; margin: 0 auto; }
        h2 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 24px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .panel { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .panel h3 { font-size: 1rem; font-weight: 700; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9; }
        .panel .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f8fafc; font-size: .9rem; }
        .panel .row:last-child { border: none; font-weight: 700; font-size: 1rem; color: #059669; }
        .panel .row span:first-child { color: #6b7280; }
        .panel.blue   { border-top: 4px solid #1a56db; }
        .panel.purple { border-top: 4px solid #7c3aed; }
        .panel.green  { border-top: 4px solid #059669; }
        .back { display: inline-block; margin-bottom: 20px; padding: 9px 18px; background: #0f172a; color: #fff; border-radius: 8px; text-decoration: none; font-size: .88rem; font-weight: 600; }
        .back:hover { opacity: .85; }
    </style>
</head>
<body>
<header>
    <h1>📊 Situation des gains</h1>
    <nav>
        <a href="<?= site_url('operator') ?>">← Dashboard</a>
        <a href="<?= site_url('operator/montants') ?>">💹 Montants</a>
        <a href="<?= site_url('operator/historique') ?>">🗂 Historique</a>
    </nav>
</header>
<main>
    <a class="back" href="<?= site_url('operator') ?>">← Retour au dashboard</a>
    <h2>📊 Situation des gains</h2>

    <div class="grid">

        <!-- Même opérateur -->
        <div class="panel blue">
            <h3>🔵 Transferts – Même opérateur</h3>
            <div class="row"><span>Nombre de transferts</span><span><?= number_format($gainsMemeOp['nb_transferts'] ?? 0) ?></span></div>
            <div class="row"><span>Total frais collectés</span><span><?= number_format($gainsMemeOp['total_frais'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Commissions</span><span><?= number_format($gainsMemeOp['total_commissions'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Gain total</span><span><?= number_format($gainsMemeOp['gain_total'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
        </div>

        <!-- Autre opérateur -->
        <div class="panel purple">
            <h3>🟣 Transferts – Autre opérateur</h3>
            <div class="row"><span>Nombre de transferts</span><span><?= number_format($gainsAutreOp['nb_transferts'] ?? 0) ?></span></div>
            <div class="row"><span>Total frais</span><span><?= number_format($gainsAutreOp['total_frais'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Total commissions</span><span><?= number_format($gainsAutreOp['total_commissions'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Gain total</span><span><?= number_format($gainsAutreOp['gain_total'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
        </div>

        <!-- Retraits -->
        <div class="panel green">
            <h3>🟢 Retraits</h3>
            <div class="row"><span>Nombre de retraits</span><span><?= number_format($gainsRetrait['nb_retraits'] ?? 0) ?></span></div>
            <div class="row"><span>Total frais collectés</span><span><?= number_format($gainsRetrait['total_frais'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
        </div>

    </div>

    <!-- Total général -->
    <?php
        $grandTotal = ($gainsMemeOp['gain_total'] ?? 0)
                    + ($gainsAutreOp['gain_total'] ?? 0)
                    + ($gainsRetrait['total_frais'] ?? 0);
    ?>
    <div class="panel" style="border-top:4px solid #f59e0b; max-width:400px;">
        <h3>🏆 Grand Total</h3>
        <div class="row"><span>Gains totaux</span><span style="font-size:1.4rem;color:#d97706;"><?= number_format($grandTotal, 2, ',', ' ') ?> Ar</span></div>
    </div>
</main>
</body>
</html>
