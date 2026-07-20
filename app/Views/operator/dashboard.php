<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Opérateur - Simulateur Mobile Money</title>
</head>
<body>
    <h1>Simulateur Mobile Money - Espace Opérateur</h1>
    
    <div>
        <a href="<?= site_url('client/login') ?>">Accéder à l'Espace Client</a>
    </div>

    <hr>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green; font-weight: bold;">[SUCCESS] <?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red; font-weight: bold;">[ERROR] <?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <!-- SECTION 1: CONFIGURATION DES PREFIXES -->
    <h2>1. Configuration des préfixes valables</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Préfixe</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prefixes)): ?>
                <tr>
                    <td colspan="3">Aucun préfixe configuré.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= esc($p['prefixe']) ?></td>
                        <td>
                            <a href="<?= site_url('operator/prefix/delete/' . $p['id']) ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Ajouter un préfixe</h3>
    <form action="<?= site_url('operator/prefix/add') ?>" method="post">
        <?= csrf_field() ?>
        <label for="prefixe">Préfixe (ex: 033) :</label>
        <input type="text" id="prefixe" name="prefixe" required placeholder="ex: 033">
        <button type="submit">Ajouter</button>
    </form>

    <hr>

    <!-- SECTION 2: BAREME DE FRAIS -->
    <h2>2. Barèmes de frais par tranche de montant</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Type d'opération</th>
                <th>Montant Min</th>
                <th>Montant Max</th>
                <th>Frais Fixe (Ar)</th>
                <th>Frais (%)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($baremes)): ?>
                <tr>
                    <td colspan="6">Aucun barème configuré.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($baremes as $b): ?>
                    <tr>
                        <form action="<?= site_url('operator/bareme/edit') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                            <td><strong><?= esc($b['type_nom']) ?> (<?= esc($b['type_code']) ?>)</strong></td>
                            <td><input type="number" step="any" name="montant_min" value="<?= $b['montant_min'] ?>" required style="width: 100px;"></td>
                            <td><input type="number" step="any" name="montant_max" value="<?= $b['montant_max'] ?>" required style="width: 100px;"></td>
                            <td><input type="number" step="any" name="frais_fixe" value="<?= $b['frais_fixe'] ?>" style="width: 80px;"></td>
                            <td><input type="number" step="any" name="frais_pourcentage" value="<?= $b['frais_pourcentage'] ?>" style="width: 80px;"></td>
                            <td>
                                <button type="submit">Modifier</button>
                                <a href="<?= site_url('operator/bareme/delete/' . $b['id']) ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Ajouter une tranche de frais</h3>
    <form action="<?= site_url('operator/bareme/add') ?>" method="post">
        <?= csrf_field() ?>
        <label for="id_type_operation">Type d'opération :</label>
        <select id="id_type_operation" name="id_type_operation" required>
            <?php foreach ($typeOperations as $type): ?>
                <option value="<?= $type['id'] ?>"><?= esc($type['nom']) ?> (<?= esc($type['code']) ?>)</option>
            <?php endforeach; ?>
        </select>
        
        <label for="montant_min">Min :</label>
        <input type="number" step="any" id="montant_min" name="montant_min" required placeholder="0">

        <label for="montant_max">Max :</label>
        <input type="number" step="any" id="montant_max" name="montant_max" required placeholder="99999999">

        <label for="frais_fixe">Frais Fixe (Ar) :</label>
        <input type="number" step="any" id="frais_fixe" name="frais_fixe" value="0">

        <label for="frais_pourcentage">Frais % :</label>
        <input type="number" step="any" id="frais_pourcentage" name="frais_pourcentage" value="0">

        <button type="submit">Ajouter la tranche</button>
    </form>

    <hr>

    <!-- SECTION 3: SITUATION DES GAINS -->
    <h2>3. Situation des gains via les frais (Retraits et Transferts)</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Type d'opération</th>
                <th>Total des gains collectés (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Retraits (RET)</td>
                <td><strong><?= number_format($gains['RET']['total'], 2, ',', ' ') ?> Ar</strong></td>
            </tr>
            <tr>
                <td>Transferts (TRA)</td>
                <td><strong><?= number_format($gains['TRA']['total'], 2, ',', ' ') ?> Ar</strong></td>
            </tr>
            <tr>
                <td>Dépôts (DEP)</td>
                <td><strong><?= number_format($gains['DEP']['total'], 2, ',', ' ') ?> Ar</strong> (Frais généralement nuls)</td>
            </tr>
            <tr>
                <td><strong>TOTAL GÉNÉRAL</strong></td>
                <td><strong><?= number_format($gains['RET']['total'] + $gains['TRA']['total'] + $gains['DEP']['total'], 2, ',', ' ') ?> Ar</strong></td>
            </tr>
        </tbody>
    </table>

    <hr>

    <!-- SECTION 4: SITUATION DES COMPTES CLIENTS -->
    <h2>4. Situation des comptes clients</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Numéro de téléphone</th>
                <th>Solde (Ar)</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="4">Aucun compte client créé.</td>
                </tr>
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
</body>
</html>
