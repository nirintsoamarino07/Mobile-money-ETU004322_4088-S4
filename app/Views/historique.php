<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Historique – Mobile Money</title>
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
        main { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .history-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 8px 30px rgba(0,0,0,.05); border: 1px solid #e2e8f0; }
        .history-card h2 { font-size: 1.2rem; font-weight: 800; color: #0d3a8c; margin-bottom: 20px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: .88rem; }
        thead { background: #f7fafc; color: #4a5568; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #edf2f7; }
        th { font-weight: 700; }
        tr:hover td { background: #f8fafc; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: .75rem; font-weight: 700; text-transform: uppercase; }
        .badge-dep { background: #d1fae5; color: #065f46; }
        .badge-ret { background: #fee2e2; color: #991b1b; }
        .badge-tra { background: #e0f2fe; color: #0369a1; }
        .amount-pos { color: #059669; font-weight: 700; }
        .amount-neg { color: #dc2626; font-weight: 700; }
        .empty-history { text-align: center; color: #a0aec0; padding: 40px; font-style: italic; }
    </style>
</head>
<body>
<header>
    <h1>Mobile Money</h1>
    <nav>
        <a href="<?= site_url('solde') ?>">Solde</a>
        <a href="<?= site_url('depot') ?>">Dépôt</a>
        <a href="<?= site_url('retrait') ?>">Retrait</a>
        <a href="<?= site_url('transfert') ?>">Transfert</a>
        <a href="<?= site_url('multi-transfert') ?>">Envoi multiple</a>
        <a href="<?= site_url('historique') ?>" class="active">Historique Client</a>
        <a class="logout-btn" href="<?= site_url('logout') ?>">Déconnexion</a>
    </nav>
</header>
<main>
    <div class="history-card">
        <h2>🗂 Historique Personnel des Operations</h2>

        <?php if (empty($history)): ?>
            <div class="empty-history">Aucune opération enregistrée pour le moment.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Détails</th>
                        <th>Montant</th>
                        <th>Frais payés</th>
                        <th>Solde après</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $h): ?>
                        <?php
                            $isSender = ($h['client_id_expediteur'] == $client['id']);
                            $sign = '';
                            $amountClass = '';
                            
                            if ($h['type_code'] === 'DEP') {
                                $sign = '+';
                                $amountClass = 'amount-pos';
                            } elseif ($h['type_code'] === 'RET') {
                                $sign = '-';
                                $amountClass = 'amount-neg';
                            } else { // Transfert
                                if ($isSender) {
                                    $sign = '-';
                                    $amountClass = 'amount-neg';
                                } else {
                                    $sign = '+';
                                    $amountClass = 'amount-pos';
                                }
                            }
                        ?>
                        <tr>
                            <td><?= esc($h['date_transaction']) ?></td>
                            <td>
                                <?php if ($h['type_code'] === 'DEP'): ?>
                                    <span class="badge badge-dep">Dépôt</span>
                                <?php elseif ($h['type_code'] === 'RET'): ?>
                                    <span class="badge badge-ret">Retrait</span>
                                <?php else: ?>
                                    <span class="badge badge-tra">Transfert</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($h['type_code'] === 'DEP'): ?>
                                    Depuis guichet automatique
                                <?php elseif ($h['type_code'] === 'RET'): ?>
                                    Depuis guichet automatique
                                <?php else: ?>
                                    <?php if ($isSender): ?>
                                        Envoyé à <strong><?= esc($h['destinataire_tel']) ?></strong>
                                    <?php else: ?>
                                        Reçu de <strong><?= esc($h['expediteur_tel']) ?></strong>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="<?= $amountClass ?>">
                                <?= $sign ?> <?= number_format($h['montant'], 2, ',', ' ') ?> Ar
                            </td>
                            <td>
                                <?= ($isSender && $h['frais'] > 0) ? number_format($h['frais'], 2, ',', ' ') . ' Ar' : '–' ?>
                            </td>
                            <td>
                                <?= ($isSender && $h['solde_apres'] !== null) ? number_format($h['solde_apres'], 2, ',', ' ') . ' Ar' : '–' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
