<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montants envoyés – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; color: #1a202c; }
        header { background: linear-gradient(135deg, #0f172a, #1e3a5f); color: #fff; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.1rem; font-weight: 700; }
        header a { color: rgba(255,255,255,.8); text-decoration: none; margin-left: 16px; font-size: .88rem; }
        header a:hover { color: #fff; }
        main { padding: 28px; max-width: 860px; margin: 0 auto; }
        h2 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 24px; }
        .back { display: inline-block; margin-bottom: 20px; padding: 9px 18px; background: #0f172a; color: #fff; border-radius: 8px; text-decoration: none; font-size: .88rem; font-weight: 600; }
        .back:hover { opacity: .85; }
        .card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        thead { background: #0f172a; color: #fff; }
        th, td { padding: 13px 16px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        tr:last-child td { border: none; font-weight: 700; background: #f8fafc; }
        tr:hover td { background: #f0f9ff; }
        .bar-wrap { background: #e5e7eb; border-radius: 99px; height: 8px; overflow: hidden; margin-top: 6px; }
        .bar-fill  { height: 100%; background: linear-gradient(90deg, #1a56db, #7c3aed); border-radius: 99px; }
        p.empty { color: #9ca3af; text-align: center; padding: 30px; }
    </style>
</head>
<body>
<header>
    <h1>💹 Montants envoyés par opérateur</h1>
    <nav>
        <a href="<?= site_url('operator') ?>">← Dashboard</a>
        <a href="<?= site_url('operator/gains') ?>">📊 Gains</a>
        <a href="<?= site_url('operator/historique') ?>">🗂 Historique</a>
    </nav>
</header>
<main>
    <a class="back" href="<?= site_url('operator') ?>">← Retour au dashboard</a>
    <h2>💹 Montants envoyés par opérateur</h2>

    <div class="card">
        <?php if (empty($montants)): ?>
            <p class="empty">Aucun transfert enregistré pour l'instant.</p>
        <?php else: ?>
            <?php
                $maxMontant = max(array_column($montants, 'montant_total'));
                $grandTotal  = array_sum(array_column($montants, 'montant_total'));
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Opérateur destinataire</th>
                        <th>Nb transferts</th>
                        <th>Montant total envoyé</th>
                        <th>Part (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($montants as $m): ?>
                        <?php $pct = $grandTotal > 0 ? round($m['montant_total'] / $grandTotal * 100, 1) : 0; ?>
                        <tr>
                            <td><strong><?= esc($m['operateur']) ?></strong></td>
                            <td><?= number_format($m['nb_transferts']) ?></td>
                            <td>
                                <?= number_format($m['montant_total'], 2, ',', ' ') ?> Ar
                                <div class="bar-wrap">
                                    <div class="bar-fill" style="width:<?= ($maxMontant > 0 ? round($m['montant_total']/$maxMontant*100) : 0) ?>%;"></div>
                                </div>
                            </td>
                            <td><?= $pct ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><strong>TOTAL</strong></td>
                        <td><strong><?= number_format(array_sum(array_column($montants, 'nb_transferts'))) ?></strong></td>
                        <td><strong><?= number_format($grandTotal, 2, ',', ' ') ?> Ar</strong></td>
                        <td><strong>100 %</strong></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
