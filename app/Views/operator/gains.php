<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains – Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-operator.css') ?>">
</head>
<body>
<header>
    <h1>Situation des gains</h1>
    <nav>
        <a href="<?= site_url('operator') ?>">← Dashboard</a>
        <a href="<?= site_url('operator/montants') ?>">Montants</a>
        <a href="<?= site_url('operator/historique') ?>">Historique</a>
    </nav>
</header>
<main>
    
    <a class="back" href="<?= site_url('operator') ?>">← Retour au dashboard</a>
    <h2>Situation des gains</h2>

    <div class="grid">

        <!-- Même opérateur -->
        <div class="panel blue">
            <h3>Transferts – Même opérateur</h3>
            <div class="row"><span>Nombre de transferts</span><span><?= number_format($gainsMemeOp['nb_transferts'] ?? 0) ?></span></div>
            <div class="row"><span>Total frais collectés</span><span><?= number_format($gainsMemeOp['total_frais'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Commissions</span><span><?= number_format($gainsMemeOp['total_commissions'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Gain total</span><span><?= number_format($gainsMemeOp['gain_total'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
        </div>

        <!-- Autre opérateur -->
        <div class="panel purple">
            <h3>Transferts – Autre opérateur</h3>
            <div class="row"><span>Nombre de transferts</span><span><?= number_format($gainsAutreOp['nb_transferts'] ?? 0) ?></span></div>
            <div class="row"><span>Total frais</span><span><?= number_format($gainsAutreOp['total_frais'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Total commissions</span><span><?= number_format($gainsAutreOp['total_commissions'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
            <div class="row"><span>Gain total</span><span><?= number_format($gainsAutreOp['gain_total'] ?? 0, 2, ',', ' ') ?> Ar</span></div>
        </div>

        <!-- Retraits -->
        <div class="panel green">
            <h3>Retraits</h3>
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
