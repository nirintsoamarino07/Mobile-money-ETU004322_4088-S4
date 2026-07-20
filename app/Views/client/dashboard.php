<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Client – Mobile Money</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; color: #1a202c; min-height: 100vh; }

        /* ---- HEADER ---- */
        header { background: linear-gradient(135deg, #1a56db 0%, #0d3a8c 100%); color: #fff; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,.25); }
        header h1 { font-size: 1.2rem; font-weight: 700; letter-spacing: .5px; }
        header nav a { color: rgba(255,255,255,.85); text-decoration: none; margin-left: 18px; font-size: .9rem; transition: color .2s; }
        header nav a:hover { color: #fff; }

        /* ---- ALERTS ---- */
        .alert { padding: 12px 18px; border-radius: 8px; margin: 16px 24px; font-size: .9rem; }
        .alert-success { background: #d1fae5; border-left: 4px solid #059669; color: #065f46; }
        .alert-error   { background: #fee2e2; border-left: 4px solid #dc2626; color: #7f1d1d; }

        /* ---- MAIN ---- */
        main { padding: 24px; max-width: 1300px; margin: 0 auto; }

        /* ---- SOLDE CARD ---- */
        .solde-card { background: linear-gradient(135deg, #1a56db, #0d3a8c); color: #fff; border-radius: 16px; padding: 28px 36px; display: inline-flex; flex-direction: column; gap: 6px; margin-bottom: 28px; box-shadow: 0 8px 24px rgba(26,86,219,.35); }
        .solde-card .label { font-size: .85rem; opacity: .8; text-transform: uppercase; letter-spacing: 1px; }
        .solde-card .amount { font-size: 2.4rem; font-weight: 700; }
        .solde-card .tel { font-size: .9rem; opacity: .75; }

        /* ---- SECTION TITLE ---- */
        .section-title { font-size: 1.15rem; font-weight: 700; color: #1a56db; border-left: 4px solid #1a56db; padding-left: 12px; margin: 28px 0 16px; }

        /* ---- OPERATION GRID ---- */
        .ops-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 12px; }
        .op-card { background: #fff; border-radius: 14px; padding: 22px; box-shadow: 0 2px 12px rgba(0,0,0,.07); border-top: 4px solid #1a56db; }
        .op-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 14px; color: #1e3a8a; }
        .op-card label { display: block; font-size: .82rem; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .op-card input[type="text"],
        .op-card input[type="number"],
        .op-card textarea { width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: .9rem; outline: none; transition: border-color .2s; }
        .op-card input:focus, .op-card textarea:focus { border-color: #1a56db; }
        .op-card textarea { resize: vertical; min-height: 80px; font-family: inherit; }
        .op-card .check-label { display: flex; align-items: center; gap: 8px; font-size: .85rem; color: #374151; margin-top: 10px; cursor: pointer; }
        .op-card .check-label input[type="checkbox"] { width: 16px; height: 16px; accent-color: #1a56db; }
        .op-card button { margin-top: 16px; width: 100%; padding: 10px; background: #1a56db; color: #fff; border: none; border-radius: 8px; font-size: .9rem; font-weight: 600; cursor: pointer; transition: background .2s; }
        .op-card button:hover { background: #1447ba; }

        /* ---- HISTORIQUE ---- */
        table.hist { width: 100%; border-collapse: collapse; background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.07); font-size: .85rem; }
        table.hist thead { background: #1a56db; color: #fff; }
        table.hist th { padding: 12px 14px; text-align: left; font-weight: 600; }
        table.hist td { padding: 11px 14px; border-bottom: 1px solid #f1f5f9; }
        table.hist tr:last-child td { border-bottom: none; }
        table.hist tr:hover td { background: #f8fafc; }
        .badge-dep  { background:#d1fae5; color:#065f46; padding:2px 10px; border-radius:99px; font-size:.78rem; font-weight:700; }
        .badge-ret  { background:#fee2e2; color:#7f1d1d; padding:2px 10px; border-radius:99px; font-size:.78rem; font-weight:700; }
        .badge-tra  { background:#dbeafe; color:#1e3a8a; padding:2px 10px; border-radius:99px; font-size:.78rem; font-weight:700; }
        .plus  { color:#059669; font-weight:700; }
        .minus { color:#dc2626; font-weight:700; }
    </style>
</head>
<body>

<header>
    <h1>📱 Mobile Money – Espace Client</h1>
    <nav>
        <span>📞 <?= esc($client['telephone']) ?></span>
        <a href="<?= site_url('operator') ?>">Espace Opérateur</a>
        <a href="<?= site_url('client/logout') ?>">Se déconnecter</a>
    </nav>
</header>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<main>

    <!-- ── SOLDE ─────────────────────────────────────────────── -->
    <div class="solde-card">
        <span class="label">Solde actuel</span>
        <span class="amount"><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</span>
        <span class="tel">📞 <?= esc($client['telephone']) ?></span>
    </div>

    <!-- ── OPÉRATIONS ────────────────────────────────────────── -->
    <div class="section-title">Faire une opération</div>
    <div class="ops-grid">

        <!-- Dépôt -->
        <div class="op-card">
            <h3>💰 Dépôt</h3>
            <form action="<?= site_url('client/deposit') ?>" method="post">
                <?= csrf_field() ?>
                <label for="dep_amount">Montant à déposer (Ar)</label>
                <input type="number" id="dep_amount" name="amount" required min="1" placeholder="Ex : 20000">
                <button type="submit">Déposer</button>
            </form>
        </div>

        <!-- Retrait -->
        <div class="op-card">
            <h3>💸 Retrait</h3>
            <form action="<?= site_url('client/withdraw') ?>" method="post">
                <?= csrf_field() ?>
                <label for="wit_amount">Montant à retirer (Ar)</label>
                <input type="number" id="wit_amount" name="amount" required min="1" placeholder="Ex : 10000">
                <button type="submit">Retirer</button>
            </form>
        </div>

        <!-- Transfert simple -->
        <div class="op-card" style="border-top-color:#7c3aed;">
            <h3>📤 Transfert</h3>
            <form action="<?= site_url('client/transfer') ?>" method="post">
                <?= csrf_field() ?>
                <label for="dest_tel">Numéro destinataire</label>
                <input type="text" id="dest_tel" name="telephone_dest" required placeholder="Ex : 0341234567">
                <label for="tra_amount" style="margin-top:12px;">Montant (Ar)</label>
                <input type="number" id="tra_amount" name="amount" required min="1" placeholder="Ex : 20000">
                <label class="check-label">
                    <input type="checkbox" name="inclure_frais_retrait" value="1" id="inclure_ret">
                    ☑ Inclure les frais de retrait (même opérateur uniquement)
                </label>
                <button type="submit" style="background:#7c3aed;">Transférer</button>
            </form>
        </div>

        <!-- Envoi Multiple -->
        <div class="op-card" style="border-top-color:#d97706;">
            <h3>📡 Envoi Multiple</h3>
            <p style="font-size:.8rem;color:#6b7280;margin-bottom:12px;">Même opérateur uniquement. Le montant total est divisé équitablement.</p>
            <form action="<?= site_url('client/multi-transfer') ?>" method="post">
                <?= csrf_field() ?>
                <label for="multi_montant">Montant total (Ar)</label>
                <input type="number" id="multi_montant" name="montant_total" required min="1" placeholder="Ex : 90000">
                <label for="multi_dest" style="margin-top:12px;">Destinataires (1 numéro par ligne)</label>
                <textarea id="multi_dest" name="destinataires" required placeholder="0341111111&#10;0342222222&#10;0343333333"></textarea>
                <button type="submit" style="background:#d97706;">Envoyer à tous</button>
            </form>
        </div>

    </div>

    <!-- ── HISTORIQUE ─────────────────────────────────────────── -->
    <div class="section-title">Historique de mes opérations</div>
    <table class="hist">
        <thead>
            <tr>
                <th>Date / Heure</th>
                <th>Type</th>
                <th>Détails</th>
                <th>Montant</th>
                <th>Frais</th>
                <th>Commission</th>
                <th>Solde après</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($history)): ?>
                <tr><td colspan="7" style="text-align:center;padding:24px;color:#9ca3af;">Aucune opération enregistrée.</td></tr>
            <?php else: ?>
                <?php foreach ($history as $h): ?>
                    <?php
                        $isExp = ($h['client_id_expediteur'] == $client['id']);
                        $sign  = '';
                        if ($h['type_code'] === 'DEP') $sign = '+';
                        elseif ($h['type_code'] === 'RET') $sign = '-';
                        elseif ($h['type_code'] === 'TRA') $sign = $isExp ? '-' : '+';
                    ?>
                    <tr>
                        <td><?= esc($h['date_transaction']) ?></td>
                        <td>
                            <?php if ($h['type_code'] === 'DEP'): ?><span class="badge-dep">Dépôt</span>
                            <?php elseif ($h['type_code'] === 'RET'): ?><span class="badge-ret">Retrait</span>
                            <?php elseif ($h['type_code'] === 'TRA'): ?><span class="badge-tra">Transfert</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($h['type_code'] === 'DEP'): ?>
                                Reçu sur mon compte
                            <?php elseif ($h['type_code'] === 'RET'): ?>
                                Retiré de mon compte
                            <?php elseif ($h['type_code'] === 'TRA'): ?>
                                <?= $isExp
                                    ? 'Envoyé vers <strong>' . esc($h['destinataire_tel']) . '</strong>'
                                    : 'Reçu de <strong>'    . esc($h['expediteur_tel'])   . '</strong>'
                                ?>
                            <?php endif; ?>
                        </td>
                        <td class="<?= $sign === '+' ? 'plus' : 'minus' ?>">
                            <?= $sign ?><?= number_format($h['montant'], 2, ',', ' ') ?> Ar
                        </td>
                        <td>
                            <?= ($isExp && $h['frais'] > 0)
                                ? number_format($h['frais'], 2, ',', ' ') . ' Ar'
                                : '–'
                            ?>
                        </td>
                        <td>
                            <?= ($isExp && $h['commission'] > 0)
                                ? number_format($h['commission'], 2, ',', ' ') . ' Ar'
                                : '–'
                            ?>
                        </td>
                        <td>
                            <?= $h['solde_apres'] !== null
                                ? number_format($h['solde_apres'], 2, ',', ' ') . ' Ar'
                                : '–'
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</main>
</body>
</html>
