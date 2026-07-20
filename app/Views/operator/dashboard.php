<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Opérateur – Send Vola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --blue:#2563eb; --blue-dark:#1e3a8a; --blue-light:#eff6ff; --blue-mid:#dbeafe; }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Inter',sans-serif; background:#f1f5f9; color:#1e293b; min-height:100vh; }

        /* HEADER */
        header {
            background:linear-gradient(135deg, var(--blue-dark), var(--blue));
            padding:24px 40px; display:flex; align-items:center; justify-content:space-between;
            box-shadow:0 4px 20px rgba(37,99,235,.3);
        }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand-icon {
            width:42px; height:42px; background:rgba(255,255,255,.2);
            border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; color:#fff;
        }
        .brand-name { font-size:1.1rem; font-weight:800; color:#fff; }
        .brand-sub  { font-size:.65rem; color:rgba(255,255,255,.65); text-transform:uppercase; letter-spacing:.1em; }
        .btn-client {
            background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.3);
            color:#fff; padding:8px 18px; border-radius:50px; font-size:.82rem;
            font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;
            transition:background .2s;
        }
        .btn-client:hover { background:rgba(255,255,255,.28); color:#fff; }

        /* LAYOUT */
        main { max-width:1100px; margin:0 auto; padding:32px 24px 60px; }

        /* FLASH */
        .flash {
            display:flex; align-items:center; gap:10px; padding:12px 18px;
            border-radius:10px; font-size:.875rem; font-weight:500; margin-bottom:22px;
        }
        .flash.ok  { background:#eff6ff; border-left:4px solid var(--blue); color:var(--blue-dark); }
        .flash.err { background:#fef2f2; border-left:4px solid #ef4444; color:#991b1b; }

        /* STAT CARDS */
        .stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
        .stat {
            background:#fff; border-radius:14px; padding:20px;
            display:flex; align-items:center; gap:14px;
            box-shadow:0 2px 12px rgba(37,99,235,.07);
            border:1px solid #e2e8f0;
            transition:transform .2s, box-shadow .2s;
        }
        .stat:hover { transform:translateY(-2px); box-shadow:0 6px 22px rgba(37,99,235,.12); }
        .stat-ico {
            width:44px; height:44px; background:var(--blue-mid);
            border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:1.1rem; color:var(--blue); flex-shrink:0;
        }
        .stat-lbl { font-size:.7rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.07em; }
        .stat-val { font-size:1.25rem; font-weight:800; color:#0f172a; margin-top:2px; }

        /* GAINS */
        .gains-row { display:flex; gap:0; }
        .gain-item { flex:1; padding:18px 24px; }
        .gain-item + .gain-item { border-left:1px solid #e2e8f0; }
        .gain-lbl { font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.07em; }
        .gain-val { font-size:1.6rem; font-weight:800; color:var(--blue); margin-top:4px; }

        /* SECTION CARD */
        .sc { background:#fff; border-radius:14px; border:1px solid #e2e8f0; margin-bottom:18px; overflow:hidden; box-shadow:0 2px 12px rgba(37,99,235,.06); }
        .sc-head { padding:14px 22px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
        .sc-title { font-size:.9rem; font-weight:700; color:#0f172a; display:flex; align-items:center; gap:8px; }
        .sc-title i { color:var(--blue); }
        .sc-body { padding:18px 22px; }

        /* TABLE */
        table { width:100%; border-collapse:separate; border-spacing:0 4px; }
        thead th { font-size:.68rem; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; font-weight:600; padding:0 12px 8px; }
        tbody tr { background:#f8faff; border-radius:8px; transition:background .15s; }
        tbody tr:hover { background:var(--blue-mid); }
        td { padding:10px 12px; font-size:.875rem; vertical-align:middle; }
        td:first-child { border-radius:8px 0 0 8px; }
        td:last-child  { border-radius:0 8px 8px 0; }

        /* BADGE */
        .badge-type {
            display:inline-block; padding:3px 9px; border-radius:6px;
            font-size:.7rem; font-weight:700; text-transform:uppercase;
            background:var(--blue-mid); color:var(--blue);
        }

        /* INPUTS */
        input[type=text], input[type=number], select {
            background:#f8faff; border:1.5px solid #e2e8f0; color:#1e293b;
            border-radius:8px; padding:7px 11px; font-size:.85rem;
            font-family:'Inter',sans-serif; width:100%; outline:none; transition:border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(37,99,235,.12); }

        /* BUTTONS */
        .btn-add {
            background:linear-gradient(135deg,var(--blue-dark),var(--blue));
            color:#fff; border:none; border-radius:8px; padding:8px 18px;
            font-size:.85rem; font-weight:600; cursor:pointer;
            display:inline-flex; align-items:center; gap:6px;
            font-family:'Inter',sans-serif; white-space:nowrap; transition:opacity .2s;
            box-shadow:0 3px 10px rgba(37,99,235,.3);
        }
        .btn-add:hover { opacity:.85; }
        .btn-save {
            background:var(--blue-mid); border:1.5px solid var(--blue);
            color:var(--blue); border-radius:7px; padding:5px 12px;
            font-size:.78rem; font-weight:700; cursor:pointer; font-family:'Inter',sans-serif;
            transition:background .2s;
        }
        .btn-save:hover { background:var(--blue); color:#fff; }
        .btn-del {
            background:#fff; border:1.5px solid #e2e8f0;
            color:#94a3b8; border-radius:7px; padding:5px 12px;
            font-size:.78rem; font-weight:700; text-decoration:none; display:inline-block;
            transition:border-color .2s, color .2s;
        }
        .btn-del:hover { border-color:#ef4444; color:#ef4444; }

        /* ADD ROW */
        .add-row { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-top:16px; padding-top:16px; border-top:1px solid #e2e8f0; }
        .add-row .f { flex:1; min-width:80px; }

        @media(max-width:768px) {
            header { padding:20px; flex-direction:column; gap:12px; }
            .stats  { grid-template-columns:1fr; }
            .gains-row { flex-direction:column; }
            .gain-item + .gain-item { border-left:none; border-top:1px solid #e2e8f0; }
        }
    </style>
</head>
<body>

<header>
    <div class="brand">
        <div class="brand-icon"><i class="bi bi-building"></i></div>
        <div>
            <div class="brand-name">Espace Opérateur</div>
            <div class="brand-sub">Send Vola · Mobile Money</div>
        </div>
    </div>
    <a href="<?= site_url('client/login') ?>" class="btn-client">
        <i class="bi bi-person-fill"></i> Espace Client
    </a>
</header>

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

<!-- STATS -->
<div class="stats">
    <div class="stat">
        <div class="stat-ico"><i class="bi bi-cash-coin"></i></div>
        <div><div class="stat-lbl">Gains totaux</div>
             <div class="stat-val"><?= number_format($totalGain, 0, ',', ' ') ?> Ar</div></div>
    </div>
    <div class="stat">
        <div class="stat-ico"><i class="bi bi-people-fill"></i></div>
        <div><div class="stat-lbl">Clients inscrits</div>
             <div class="stat-val"><?= $nbClients ?></div></div>
    </div>
    <div class="stat">
        <div class="stat-ico"><i class="bi bi-sliders"></i></div>
        <div><div class="stat-lbl">Barèmes actifs</div>
             <div class="stat-val"><?= $nbBaremes ?></div></div>
    </div>
</div>

<!-- GAINS DETAIL -->
<div class="sc" style="margin-bottom:18px;">
    <div class="sc-head"><div class="sc-title"><i class="bi bi-graph-up-arrow"></i> Détail des gains</div></div>
    <div class="gains-row">
        <div class="gain-item">
            <div class="gain-lbl">Frais Retraits</div>
            <div class="gain-val"><?= number_format($gainRet, 0, ',', ' ') ?> Ar</div>
        </div>
        <div class="gain-item">
            <div class="gain-lbl">Frais Transferts</div>
            <div class="gain-val"><?= number_format($gainTra, 0, ',', ' ') ?> Ar</div>
        </div>
        <div class="gain-item" style="background:var(--blue-light);">
            <div class="gain-lbl">Total cumulé</div>
            <div class="gain-val" style="color:var(--blue-dark);"><?= number_format($totalGain, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>
</div>

<!-- PRÉFIXES -->
<div class="sc">
    <div class="sc-head">
        <div class="sc-title"><i class="bi bi-telephone-fill"></i> Préfixes autorisés</div>
    </div>
    <div class="sc-body">
        <table>
            <thead><tr><th>#</th><th>Préfixe</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($prefixes)): ?>
                <tr><td colspan="3" style="color:#94a3b8; text-align:center; padding:18px;">Aucun préfixe configuré</td></tr>
            <?php else: ?>
                <?php foreach ($prefixes as $p): ?>
                <tr>
                    <td style="color:#94a3b8;"><?= $p['id'] ?></td>
                    <td><strong style="font-family:monospace; font-size:1rem; color:var(--blue);"><?= esc($p['prefixe']) ?></strong></td>
                    <td><a href="<?= site_url('operator/prefix/delete/'.$p['id']) ?>" class="btn-del"><i class="bi bi-trash3"></i> Supprimer</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <form action="<?= site_url('operator/prefix/add') ?>" method="post" class="add-row">
            <?= csrf_field() ?>
            <div class="f"><input type="text" name="prefixe" placeholder="ex : 038" required></div>
            <button type="submit" class="btn-add"><i class="bi bi-plus-lg"></i> Ajouter</button>
        </form>
    </div>
</div>

<!-- BARÈMES -->
<div class="sc">
    <div class="sc-head"><div class="sc-title"><i class="bi bi-sliders"></i> Barèmes de frais</div></div>
    <div class="sc-body">
        <div style="overflow-x:auto;">
        <table>
            <thead><tr><th>Type</th><th>Min (Ar)</th><th>Max (Ar)</th><th>Fixe (Ar)</th><th>% Frais</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($baremes as $b): ?>
            <tr>
                <form action="<?= site_url('operator/bareme/edit') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    <td><span class="badge-type"><?= esc($b['type_code']) ?></span></td>
                    <td><input type="number" name="montant_min" value="<?= $b['montant_min'] ?>" style="min-width:90px;"></td>
                    <td><input type="number" name="montant_max" value="<?= $b['montant_max'] ?>" style="min-width:90px;"></td>
                    <td><input type="number" name="frais_fixe" value="<?= $b['frais_fixe'] ?>" style="min-width:80px;"></td>
                    <td><input type="number" name="frais_pourcentage" value="<?= $b['frais_pourcentage'] ?>" style="min-width:60px;" step="0.01"></td>
                    <td style="display:flex; gap:6px; padding-top:13px;">
                        <button type="submit" class="btn-save"><i class="bi bi-check-lg"></i> Sauv.</button>
                        <a href="<?= site_url('operator/bareme/delete/'.$b['id']) ?>" class="btn-del"><i class="bi bi-trash3"></i></a>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <form action="<?= site_url('operator/bareme/add') ?>" method="post" class="add-row">
            <?= csrf_field() ?>
            <div class="f">
                <select name="id_type_operation">
                    <?php foreach ($typeOperations as $type): ?>
                    <option value="<?= $type['id'] ?>"><?= esc($type['code']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="f"><input type="number" name="montant_min" placeholder="Min"></div>
            <div class="f"><input type="number" name="montant_max" placeholder="Max"></div>
            <div class="f"><input type="number" name="frais_fixe" placeholder="Fixe (Ar)"></div>
            <div class="f"><input type="number" name="frais_pourcentage" placeholder="%" step="0.01"></div>
            <button type="submit" class="btn-add"><i class="bi bi-plus-lg"></i> Ajouter</button>
        </form>
    </div>
</div>

<!-- CLIENTS -->
<div class="sc">
    <div class="sc-head">
        <div class="sc-title"><i class="bi bi-people-fill"></i> Liste des clients</div>
        <span style="font-size:.78rem; color:#94a3b8;"><?= $nbClients ?> compte<?= $nbClients>1?'s':'' ?></span>
    </div>
    <div class="sc-body">
        <div style="overflow-x:auto;">
        <table>
            <thead><tr><th>#</th><th>Téléphone</th><th>Solde</th><th>Inscrit le</th></tr></thead>
            <tbody>
            <?php foreach ($clients as $c): ?>
            <tr>
                <td style="color:#94a3b8; font-size:.8rem;"><?= $c['id'] ?></td>
                <td><strong style="font-family:monospace; color:var(--blue);"><?= esc($c['telephone']) ?></strong></td>
                <td style="font-weight:700; color:var(--blue-dark);"><?= number_format($c['solde'], 0, ',', ' ') ?> Ar</td>
                <td style="color:#94a3b8; font-size:.8rem;"><?= $c['date_creation'] ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

</main>
</body>
</html>