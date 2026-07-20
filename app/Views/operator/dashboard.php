<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Opérateur – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; color: #1a202c; }
        header { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); color: #fff; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.15rem; font-weight: 700; }
        header nav a { color: rgba(255,255,255,.8); text-decoration: none; margin-left: 16px; font-size: .88rem; transition: color .2s; }
        header nav a:hover { color: #fff; }
        .alert { padding: 12px 18px; border-radius: 8px; margin: 14px 24px; font-size: .9rem; }
        .alert-success { background:#d1fae5; border-left:4px solid #059669; color:#065f46; }
        .alert-error   { background:#fee2e2; border-left:4px solid #dc2626; color:#7f1d1d; }
        main { padding: 24px; max-width: 1400px; margin: 0 auto; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; border-left: 4px solid #0f172a; padding-left: 12px; margin: 28px 0 16px; }
        .card { background: #fff; border-radius: 14px; padding: 22px; box-shadow: 0 2px 12px rgba(0,0,0,.07); margin-bottom: 24px; }
        /* Tables */
        table.data { width: 100%; border-collapse: collapse; font-size: .87rem; }
        table.data thead { background: #0f172a; color: #fff; }
        table.data th, table.data td { padding: 11px 14px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        table.data tr:last-child td { border-bottom: none; }
        table.data tr:hover td { background: #f8fafc; }
        /* Forms */
        .form-inline { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; margin-top: 14px; }
        .form-inline label { font-size: .8rem; font-weight: 600; color: #4b5563; display: block; margin-bottom: 4px; }
        .form-inline input, .form-inline select { padding: 8px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: .88rem; outline: none; }
        .form-inline input:focus, .form-inline select:focus { border-color: #0f172a; }
        .btn { padding: 8px 18px; border: none; border-radius: 8px; font-size: .88rem; font-weight: 600; cursor: pointer; transition: opacity .2s; }
        .btn-primary   { background: #0f172a; color: #fff; }
        .btn-danger    { background: #dc2626; color: #fff; }
        .btn-warning   { background: #d97706; color: #fff; }
        .btn-sm { padding: 5px 12px; font-size: .8rem; }
        .btn:hover { opacity: .85; }
        /* Stats cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,.07); border-top: 4px solid #1a56db; }
        .stat-card .s-label { font-size: .75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }
        .stat-card .s-val { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-top: 6px; }
        /* Nav links */
        .quick-links { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .quick-links a { padding: 10px 20px; background: #1a56db; color: #fff; border-radius: 8px; text-decoration: none; font-size: .88rem; font-weight: 600; transition: background .2s; }
        .quick-links a:hover { background: #1447ba; }
    </style>
</head>
<body>

<header>
    <h1>🔧 Mobile Money – Espace Opérateur</h1>
    <nav>
        <a href="<?= site_url('client/login') ?>">👤 Espace Client</a>
        <a href="<?= site_url('operator/gains') ?>">📊 Gains</a>
        <a href="<?= site_url('operator/montants') ?>">💹 Montants</a>
        <a href="<?= site_url('operator/historique') ?>">🗂 Historique</a>
    </nav>
</header>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<main>

    <!-- Stats rapides -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="s-label">Total Frais Retraits</div>
            <div class="s-val"><?= number_format($gains['RET']['total_frais'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
        <div class="stat-card" style="border-top-color:#7c3aed;">
            <div class="s-label">Total Frais Transferts</div>
            <div class="s-val"><?= number_format($gains['TRA']['total_frais'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
        <div class="stat-card" style="border-top-color:#d97706;">
            <div class="s-label">Total Commissions</div>
            <div class="s-val"><?= number_format($gains['TRA']['total_commissions'] ?? 0, 0, ',', ' ') ?> Ar</div>
        </div>
        <div class="stat-card" style="border-top-color:#059669;">
            <div class="s-label">Nb Clients</div>
            <div class="s-val"><?= count($clients) ?></div>
        </div>
    </div>

    <div class="quick-links">
        <a href="<?= site_url('operator/gains') ?>">📊 Situation des gains</a>
        <a href="<?= site_url('operator/montants') ?>">💹 Montants envoyés</a>
        <a href="<?= site_url('operator/historique') ?>">🗂 Historique complet</a>
    </div>

    <!-- ── 1. OPÉRATEURS ──────────────────────────────────────── -->
    <div class="section-title">1. Opérateurs et commissions</div>
    <div class="card">
        <table class="data">
            <thead><tr><th>ID</th><th>Opérateur</th><th>Commission (%)</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if (empty($operateurs)): ?>
                    <tr><td colspan="4" style="color:#9ca3af;text-align:center;">Aucun opérateur.</td></tr>
                <?php else: ?>
                    <?php foreach ($operateurs as $op): ?>
                        <tr>
                            <td><?= $op['id'] ?></td>
                            <td><strong><?= esc($op['nom']) ?></strong></td>
                            <td><?= $op['pourcentage_commission'] ?> %</td>
                            <td>
                                <!-- Edit inline -->
                                <form action="<?= site_url('operator/operateur/edit') ?>" method="post" style="display:inline-flex;gap:6px;align-items:center;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $op['id'] ?>">
                                    <input type="text"   name="nom" value="<?= esc($op['nom']) ?>" style="width:90px;padding:4px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;">
                                    <input type="number" name="pourcentage_commission" value="<?= $op['pourcentage_commission'] ?>" min="0" max="100" step="0.01" style="width:70px;padding:4px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;">
                                    <button type="submit" class="btn btn-warning btn-sm">Modifier</button>
                                </form>
                                <a href="<?= site_url('operator/operateur/delete/' . $op['id']) ?>" class="btn btn-danger btn-sm" style="margin-left:4px;" onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h4 style="margin-top:18px;margin-bottom:10px;font-size:.9rem;">Ajouter un opérateur</h4>
        <form action="<?= site_url('operator/operateur/add') ?>" method="post" class="form-inline">
            <?= csrf_field() ?>
            <div>
                <label>Nom</label>
                <input type="text" name="nom" required placeholder="Ex : Telma">
            </div>
            <div>
                <label>Commission (%)</label>
                <input type="number" name="pourcentage_commission" min="0" max="100" step="0.01" value="0" required style="width:100px;">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- ── 2. PRÉFIXES ────────────────────────────────────────── -->
    <div class="section-title">2. Préfixes valables</div>
    <div class="card">
        <table class="data">
            <thead><tr><th>ID</th><th>Préfixe</th><th>Opérateur</th><th>Action</th></tr></thead>
            <tbody>
                <?php if (empty($prefixes)): ?>
                    <tr><td colspan="4" style="color:#9ca3af;text-align:center;">Aucun préfixe.</td></tr>
                <?php else: ?>
                    <?php foreach ($prefixes as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><strong><?= esc($p['prefixe']) ?></strong></td>
                            <td><?= esc($p['operateur_nom'] ?? '–') ?></td>
                            <td>
                                <a href="<?= site_url('operator/prefix/delete/' . $p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h4 style="margin-top:18px;margin-bottom:10px;font-size:.9rem;">Ajouter un préfixe</h4>
        <form action="<?= site_url('operator/prefix/add') ?>" method="post" class="form-inline">
            <?= csrf_field() ?>
            <div>
                <label>Préfixe (3 chiffres)</label>
                <input type="text" name="prefixe" required placeholder="Ex : 035" maxlength="3" style="width:110px;">
            </div>
            <div>
                <label>Opérateur</label>
                <select name="id_operateur">
                    <option value="">— Aucun —</option>
                    <?php foreach ($operateurs as $op): ?>
                        <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- ── 3. BARÈME DE FRAIS ─────────────────────────────────── -->
    <div class="section-title">3. Barèmes de frais</div>
    <div class="card">
        <table class="data">
            <thead>
                <tr><th>Type</th><th>Min (Ar)</th><th>Max (Ar)</th><th>Frais fixe (Ar)</th><th>Frais %</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($baremes)): ?>
                    <tr><td colspan="6" style="color:#9ca3af;text-align:center;">Aucun barème.</td></tr>
                <?php else: ?>
                    <?php foreach ($baremes as $b): ?>
                        <tr>
                            <form action="<?= site_url('operator/bareme/edit') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <td><strong><?= esc($b['type_nom']) ?> (<?= esc($b['type_code']) ?>)</strong></td>
                                <td><input type="number" step="any" name="montant_min" value="<?= $b['montant_min'] ?>" required style="width:90px;padding:4px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;"></td>
                                <td><input type="number" step="any" name="montant_max" value="<?= $b['montant_max'] ?>" required style="width:90px;padding:4px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;"></td>
                                <td><input type="number" step="any" name="frais_fixe" value="<?= $b['frais_fixe'] ?>" style="width:80px;padding:4px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;"></td>
                                <td><input type="number" step="any" name="frais_pourcentage" value="<?= $b['frais_pourcentage'] ?>" style="width:70px;padding:4px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;"></td>
                                <td>
                                    <button type="submit" class="btn btn-warning btn-sm">Modifier</button>
                                    <a href="<?= site_url('operator/bareme/delete/' . $b['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h4 style="margin-top:18px;margin-bottom:10px;font-size:.9rem;">Ajouter une tranche</h4>
        <form action="<?= site_url('operator/bareme/add') ?>" method="post" class="form-inline">
            <?= csrf_field() ?>
            <div>
                <label>Type</label>
                <select name="id_type_operation" required>
                    <?php foreach ($typeOperations as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= esc($type['nom']) ?> (<?= esc($type['code']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div><label>Min (Ar)</label><input type="number" step="any" name="montant_min" required placeholder="0" style="width:90px;"></div>
            <div><label>Max (Ar)</label><input type="number" step="any" name="montant_max" required placeholder="10000" style="width:90px;"></div>
            <div><label>Frais fixe</label><input type="number" step="any" name="frais_fixe" value="0" style="width:80px;"></div>
            <div><label>Frais %</label><input type="number" step="any" name="frais_pourcentage" value="0" style="width:70px;"></div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- ── 4. CLIENTS ─────────────────────────────────────────── -->
    <div class="section-title">4. Comptes clients</div>
    <div class="card">
        <table class="data">
            <thead><tr><th>ID</th><th>Téléphone</th><th>Solde (Ar)</th><th>Date création</th></tr></thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr><td colspan="4" style="color:#9ca3af;text-align:center;">Aucun client.</td></tr>
                <?php else: ?>
                    <?php foreach ($clients as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><strong><?= esc($c['telephone']) ?></strong></td>
                            <td><?= number_format($c['solde'], 2, ',', ' ') ?> Ar</td>
                            <td><?= $c['date_creation'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
</body>
</html>
