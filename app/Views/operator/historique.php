<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique Opérateur – Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-operator.css') ?>">
</head>
<body>
<header>
    <h1>🗂 Historique Opérateur</h1>
    <nav>
        <a href="<?= site_url('operator') ?>">← Dashboard</a>
        <a href="<?= site_url('operator/gains') ?>">📊 Gains</a>
        <a href="<?= site_url('operator/montants') ?>">💹 Montants</a>
    </nav>
</header>
<main>
    <a class="back" href="<?= site_url('operator') ?>">← Retour au dashboard</a>
    <h2>🗂 Historique complet des opérations</h2>

    <!-- Filtres -->
    <form method="get" action="<?= site_url('operator/historique') ?>" class="filters">
        <div>
            <label>Date début</label>
            <input type="date" name="date_debut" value="<?= esc($dateDebut) ?>">
        </div>
        <div>
            <label>Date fin</label>
            <input type="date" name="date_fin" value="<?= esc($dateFin) ?>">
        </div>
        <div>
            <label>Numéro client</label>
            <input type="text" name="telephone" value="<?= esc($telClient) ?>" placeholder="Ex : 0321111111" style="width:160px;">
        </div>
        <div>
            <label>Type d'opération</label>
            <select name="type_code">
                <option value="">— Tous —</option>
                <?php foreach ($typeOps as $t): ?>
                    <option value="<?= esc($t['code']) ?>" <?= $typeCode === $t['code'] ? 'selected' : '' ?>>
                        <?= esc($t['nom']) ?> (<?= esc($t['code']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
        <a href="<?= site_url('operator/historique') ?>" class="btn btn-reset">Réinitialiser</a>
    </form>

    <!-- Tableau -->
    <div class="card">
        <?php if (empty($history)): ?>
            <p class="empty">Aucune opération trouvée pour ces critères.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date / Heure</th>
                        <th>Type</th>
                        <th>Expéditeur</th>
                        <th>Destinataire</th>
                        <th>Montant</th>
                        <th>Frais</th>
                        <th>Commission</th>
                        <th>Solde après</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $h): ?>
                        <tr>
                            <td style="color:#9ca3af;font-size:.8rem;"><?= $h['id'] ?></td>
                            <td style="white-space:nowrap;"><?= esc($h['date_transaction']) ?></td>
                            <td>
                                <?php if ($h['type_code'] === 'DEP'): ?><span class="badge-dep">Dépôt</span>
                                <?php elseif ($h['type_code'] === 'RET'): ?><span class="badge-ret">Retrait</span>
                                <?php elseif ($h['type_code'] === 'TRA'): ?><span class="badge-tra">Transfert</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $h['expediteur_tel'] ? esc($h['expediteur_tel']) : '<span style="color:#9ca3af">–</span>' ?></td>
                            <td><?= $h['destinataire_tel'] ? esc($h['destinataire_tel']) : '<span style="color:#9ca3af">–</span>' ?></td>
                            <td><strong><?= number_format($h['montant'], 2, ',', ' ') ?> Ar</strong></td>
                            <td><?= $h['frais'] > 0 ? number_format($h['frais'], 2, ',', ' ') . ' Ar' : '–' ?></td>
                            <td><?= $h['commission'] > 0 ? number_format($h['commission'], 2, ',', ' ') . ' Ar' : '–' ?></td>
                            <td><?= $h['solde_apres'] !== null ? number_format($h['solde_apres'], 2, ',', ' ') . ' Ar' : '–' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <p style="font-size:.82rem;color:#9ca3af;margin-top:12px;"><?= count($history) ?> opération(s) affichée(s).</p>
</main>
</body>
</html>
