<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Opérateur – Mobile Money</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-client.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sendvolla-operator.css') ?>">
</head>
<body>
<header>
    <h1>Mobile Money – Espace Opérateur</h1>
    <nav>
        <a href="<?= site_url('/') ?>">Espace Client</a>
        <a href="<?= site_url('operator/gains') ?>">Gains</a>
        <a href="<?= site_url('operator/montants') ?>">Montants</a>
        <a href="<?= site_url('operator/historique') ?>">Historique</a>
    </nav>
</header>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<main>  
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
            <div><label>Epargne  %</label><input type="number" step="any" name="Epargne_pourcentag"></div>

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
<main>

<?php
$gainRet   = $gains['RET']['total'] ?? 0;
$gainTra   = $gains['TRA']['total'] ?? 0;
$totalGain = $gainRet + $gainTra;
$nbClients = count($clients);
$nbBaremes = count($baremes);
?>

<!-- FLASH -->
<?php if (session()->getFlashdata('success')): ?>
<div class="flash ok"><i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="flash err"><i class="bi bi-exclamation-triangle-fill"></i><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

</main>
</body>
</html>