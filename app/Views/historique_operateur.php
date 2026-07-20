<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique Opérateur – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; color: #1a202c; }
        header { background: linear-gradient(135deg, #0f172a, #1e3a5f); color: #fff; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.1rem; font-weight: 700; }
        header a { color: rgba(255,255,255,.8); text-decoration: none; margin-left: 16px; font-size: .88rem; }
        header a:hover { color: #fff; }
        main { padding: 24px; max-width: 1400px; margin: 0 auto; }
        h2 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 20px; }
        .back { display: inline-block; margin-bottom: 16px; padding: 9px 18px; background: #0f172a; color: #fff; border-radius: 8px; text-decoration: none; font-size: .88rem; font-weight: 600; }
        .back:hover { opacity: .85; }
        /* Filtres */
        .filters { background: #fff; border-radius: 14px; padding: 20px 24px; box-shadow: 0 2px 10px rgba(0,0,0,.07); margin-bottom: 22px; display: flex; flex-wrap: wrap; gap: 14px; align-items: flex-end; }
        .filters label { font-size: .8rem; font-weight: 600; color: #4b5563; display: block; margin-bottom: 5px; }
        .filters input, .filters select { padding: 8px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: .88rem; outline: none; }
        .filters input:focus, .filters select:focus { border-color: #0f172a; }
        .btn { padding: 9px 20px; border: none; border-radius: 8px; font-size: .88rem; font-weight: 600; cursor: pointer; transition: opacity .2s; }
        .btn-primary { background: #0f172a; color: #fff; }
        .btn-reset   { background: #e5e7eb; color: #374151; }
        .btn:hover   { opacity: .85; }
        /* Table */
        .card { background: #fff; border-radius: 14px; padding: 0; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        table { width: 100%; border-collapse: collapse; font-size: .84rem; }
        thead { background: #0f172a; color: #fff; }
        th { padding: 12px 14px; text-align: left; font-weight: 600; white-space: nowrap; }
        td { padding: 11px 14px; border-bottom: 1px solid #f1f5f9; }
        tr:last-child td { border: none; }
        tr:hover td { background: #f8fafc; }
        .badge-dep  { background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:99px; font-size:.75rem; font-weight:700; }
        .badge-ret  { background:#fee2e2; color:#7f1d1d; padding:3px 10px; border-radius:99px; font-size:.75rem; font-weight:700; }
        .badge-tra  { background:#dbeafe; color:#1e3a8a; padding:3px 10px; border-radius:99px; font-size:.75rem; font-weight:700; }
        .empty { color: #9ca3af; text-align: center; padding: 36px; font-size: .9rem; }
    </style>
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
