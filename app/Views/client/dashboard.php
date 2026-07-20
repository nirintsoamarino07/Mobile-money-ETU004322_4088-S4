<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Espace Client - Simulateur Mobile Money</title>
</head>
<body>
    <h1>Simulateur Mobile Money - Espace Client</h1>
    
    <p>
        Connecté avec le numéro : <strong><?= esc($client['telephone']) ?></strong> | 
        <a href="<?= site_url('client/logout') ?>">Se déconnecter</a> |
        <a href="<?= site_url('operator') ?>">Espace Opérateur</a>
    </p>

    <hr>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green; font-weight: bold;">[SUCCESS] <?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red; font-weight: bold;">[ERROR] <?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

   
    <h2>Mon Solde Actuell</h2>
    <h3 style="background-color: #f0f0f0; padding: 10px; display: inline-block;">
        <?= number_format($client['solde'], 2, ',', ' ') ?> Ar
    </h3>

    <hr>

    <!-- OPERATIONS -->
    <h2>Faire une opération</h2>
    
    <table border="0" cellpadding="10">
        <tr valign="top">
            <!-- Formulaire Depot -->
            <td style="border: 1px solid #ccc; padding: 15px;">
                <h3>Dépôt (Automatique)</h3>
                <form action="<?= site_url('client/deposit') ?>" method="post">
                    <?= csrf_field() ?>
                    <p>
                        <label for="dep_amount">Montant à déposer (Ar) :</label><br>
                        <input type="number" step="any" id="dep_amount" name="amount" required min="1">
                    </p>
                    <button type="submit">Déposer</button>
                </form>
            </td>

            <!-- Formulaire Retrait -->
            <td style="border: 1px solid #ccc; padding: 15px;">
                <h3>Retrait (Automatique)</h3>
                <form action="<?= site_url('client/withdraw') ?>" method="post">
                    <?= csrf_field() ?>
                    <p>
                        <label for="wit_amount">Montant à retirer (Ar) :</label><br>
                        <input type="number" step="any" id="wit_amount" name="amount" required min="1">
                    </p>
                    <button type="submit">Retirer</button>
                </form>
            </td>

            <!-- Formulaire Transfert -->
            <td style="border: 1px solid #ccc; padding: 15px;">
                <h3>Transfert</h3>
                <form action="<?= site_url('client/transfer') ?>" method="post">
                    <?= csrf_field() ?>
                    <p>
                        <label for="dest_tel">Téléphone du destinataire :</label><br>
                        <input type="text" id="dest_tel" name="telephone_dest" required placeholder="ex: 0337654321">
                    </p>
                    <p>
                        <label for="tra_amount">Montant à transférer (Ar) :</label><br>
                        <input type="number" step="any" id="tra_amount" name="amount" required min="1">
                    </p>
                    <button type="submit">Transférer</button>
                </form>
            </td>
        </tr>
    </table>

    <hr>

    <!-- HISTORIQUE -->
    <h2>Historique de mes opérations</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Date / Heure</th>
                <th>Type d'opération</th>
                <th>Détails</th>
                <th>Montant</th>
                <th>Frais payés</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($history)): ?>
                <tr>
                    <td colspan="5">Aucune opération enregistrée dans l'historique.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($history as $h): ?>
                    <tr>
                        <td><?= esc($h['date_transaction']) ?></td>
                        <td>
                            <?php if ($h['type_code'] === 'DEP'): ?>
                                <span style="color: green; font-weight: bold;">Depôt</span>
                            <?php elseif ($h['type_code'] === 'RET'): ?>
                                <span style="color: red; font-weight: bold;">Retrait</span>
                            <?php elseif ($h['type_code'] === 'TRA'): ?>
                                <span style="color: blue; font-weight: bold;">Transfert</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($h['type_code'] === 'DEP'): ?>
                                Reçu sur mon compte
                            <?php elseif ($h['type_code'] === 'RET'): ?>
                                Retiré de mon compte
                            <?php elseif ($h['type_code'] === 'TRA'): ?>
                                <?php if ($h['client_id_expediteur'] == $client['id']): ?>
                                    Envoyé vers <strong><?= esc($h['destinataire_tel']) ?></strong>
                                <?php else: ?>
                                    Reçu de <strong><?= esc($h['expediteur_tel']) ?></strong>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $sign = '';
                            if ($h['type_code'] === 'DEP') {
                                $sign = '+';
                            } elseif ($h['type_code'] === 'RET') {
                                $sign = '-';
                            } elseif ($h['type_code'] === 'TRA') {
                                $sign = ($h['client_id_expediteur'] == $client['id']) ? '-' : '+';
                            }
                            ?>
                            <strong style="color: <?= $sign === '+' ? 'green' : 'red' ?>;">
                                <?= $sign ?><?= number_format($h['montant'], 2, ',', ' ') ?> Ar
                            </strong>
                        </td>
                        <td>
                            <?php if ($h['client_id_expediteur'] == $client['id'] && $h['frais'] > 0): ?>
                                <?= number_format($h['frais'], 2, ',', ' ') ?> Ar
                            <?php else: ?>
                                0,00 Ar
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
