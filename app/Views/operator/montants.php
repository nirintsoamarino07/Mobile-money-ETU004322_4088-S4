<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montants envoyés – Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-operator.css') ?>">
</head>
<body>
<header>
    <h1>Montants envoyés par opérateur</h1>
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
