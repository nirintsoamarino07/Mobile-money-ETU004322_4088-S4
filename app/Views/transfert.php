<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert – Mobile Money</title>
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
        main { max-width: 600px; margin: 40px auto; padding: 0 20px; }
        .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: .9rem; }
        .alert-error { background: #fee2e2; border-left: 4px solid #dc2626; color: #7f1d1d; }
        .form-card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 8px 30px rgba(0,0,0,.05); border: 1px solid #e2e8f0; }
        .form-card h2 { font-size: 1.2rem; font-weight: 800; color: #0d3a8c; margin-bottom: 20px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        .client-mini-info { display: flex; justify-content: space-between; font-size: .88rem; color: #718096; background: #f7fafc; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; }
        label { display: block; font-size: .85rem; font-weight: 700; color: #4a5568; margin-bottom: 8px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 12px 16px; border: 1.5px solid #cbd5e0; border-radius: 10px; font-size: 1.05rem; outline: none; margin-bottom: 16px; }
        input[type="text"]:focus, input[type="number"]:focus { border-color: #1a56db; }
        .checkbox-container { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 24px; cursor: pointer; user-select: none; }
        .checkbox-container input { width: 18px; height: 18px; margin-top: 2px; }
        .checkbox-container label { font-size: .88rem; font-weight: 600; color: #4a5568; margin-bottom: 0; cursor: pointer; }
        .checkbox-container .desc { display: block; font-size: .78rem; color: #718096; font-weight: 500; margin-top: 4px; }
        button { width: 100%; padding: 13px; background: linear-gradient(135deg, #1a56db, #0d3a8c); color: #fff; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: opacity .2s; }
        button:hover { opacity: .9; }
        .calc-box { margin-top: 20px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 16px; font-size: .82rem; color: #1e40af; }
        .calc-box table { width: 100%; }
        .calc-box td { padding: 4px 0; }
    </style>
</head>
<body>
<header>
    <h1>📱 Mobile Money</h1>
    <nav>
        <a href="<?= site_url('solde') ?>">Solde</a>
        <a href="<?= site_url('depot') ?>">Dépôt</a>
        <a href="<?= site_url('retrait') ?>">Retrait</a>
        <a href="<?= site_url('transfert') ?>" class="active">Transfert</a>
        <a href="<?= site_url('multi-transfert') ?>">Envoi multiple</a>
        <a href="<?= site_url('historique') ?>">Historique Client</a>
        <a class="logout-btn" href="<?= site_url('logout') ?>">Déconnexion</a>
    </nav>
</header>
<main>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">❌ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="form-card">
        <h2>💸 Transférer des fonds</h2>
        <div class="client-mini-info">
            <span>Votre Numéro : <?= esc($client['telephone']) ?></span>
            <span>Solde actuel : <?= number_format($client['solde'], 2, ',', ' ') ?> Ar</span>
        </div>

        <form action="<?= site_url('transfert') ?>" method="post">
            <?= csrf_field() ?>
            <label for="telephone_dest">Numéro de téléphone du destinataire</label>
            <input type="text" id="telephone_dest" name="telephone_dest" required placeholder="Ex : 0341234567" oninput="calculerFrais()">

            <label for="amount">Montant à transférer (Ar)</label>
            <input type="number" id="amount" name="amount" required min="1" step="any" placeholder="Ex : 20000" oninput="calculerFrais()">

            <div class="checkbox-container" id="check-retrait-wrap">
                <input type="checkbox" id="inclure_frais_retrait" name="inclure_frais_retrait" value="1" onchange="calculerFrais()">
                <div>
                    <label for="inclure_frais_retrait">Inclure les frais de retrait</label>
                    <span class="desc">Si coché, vous payez les frais de retrait pour que le destinataire reçoive le montant exact sans frais appliqués au retrait. (Uniquement disponible si même opérateur).</span>
                </div>
            </div>

            <div class="calc-box" id="calc-box" style="display:none;">
                <h4 style="font-weight: 700; margin-bottom: 8px; text-transform: uppercase; font-size: .78rem; color: #1e3a8a;">Récapitulatif des frais</h4>
                <table style="width: 100%;">
                    <tr><td>Destinataire :</td><td style="text-align:right;"><span id="dest-op-label">Yas</span></td></tr>
                    <tr><td>Frais de transfert :</td><td style="text-align:right;"><span id="frais-tra">0 Ar</span></td></tr>
                    <tr id="row-comm"><td>Commission inter-opérateur :</td><td style="text-align:right;"><span id="frais-comm">0 Ar</span></td></tr>
                    <tr id="row-ret"><td>Frais de retrait inclus :</td><td style="text-align:right;"><span id="frais-ret">0 Ar</span></td></tr>
                    <tr style="border-top:1px solid #bfdbfe; font-weight:700; color:#1e3a8a;">
                        <td style="padding-top:6px;">Total débité :</td>
                        <td style="text-align:right; padding-top:6px;"><span id="total-debite">0 Ar</span></td>
                    </tr>
                </table>
            </div>

            <button type="submit" style="margin-top:20px;">Confirmer le Transfert</button>
        </form>
    </div>
</main>

<script>
// Préfixes déclarés
var codePrefixes = {
    '034': {op: 'Yas', comm: 30},
    '038': {op: 'Yas', comm: 30},
    '033': {op: 'Airtel', comm: 50},
    '032': {op: 'Orange', comm: 20},
    '037': {op: 'Orange', comm: 20}
};

var expediteurTel = "<?= esc($client['telephone']) ?>";
var expedPrefix = expediteurTel.substring(0, 3);
var expedOp = codePrefixes[expedPrefix] ? codePrefixes[expedPrefix].op : null;

function calculerFrais() {
    var dest = document.getElementById('telephone_dest').value.trim();
    var mont = parseFloat(document.getElementById('amount').value);
    var inclureRet = document.getElementById('inclure_frais_retrait').checked;
    
    var calcBox = document.getElementById('calc-box');
    var checkRetWrap = document.getElementById('check-retrait-wrap');
    
    if (dest.length < 3 || isNaN(mont) || mont <= 0) {
        calcBox.style.display = 'none';
        return;
    }
    
    var destPrefix = dest.substring(0, 3);
    var destOpObj = codePrefixes[destPrefix];
    
    if (!destOpObj) {
        document.getElementById('dest-op-label').innerText = "Inconnu";
        calcBox.style.display = 'block';
        document.getElementById('frais-tra').innerText = "–";
        document.getElementById('frais-comm').innerText = "–";
        document.getElementById('frais-ret').innerText = "–";
        document.getElementById('total-debite').innerText = "–";
        return;
    }
    
    document.getElementById('dest-op-label').innerText = destOpObj.op;
    
    // Frais transfert
    var fraisTra = 0;
    if (mont <= 10000) fraisTra = 200;
    else if (mont <= 50000) fraisTra = 500;
    else if (mont <= 100000) fraisTra = 800;
    else fraisTra = 0;
    
    var comm = 0;
    var fraisRet = 0;
    
    if (expedOp === destOpObj.op) {
        // Même opérateur : option frais retrait possible
        checkRetWrap.style.opacity = '1';
        document.getElementById('inclure_frais_retrait').disabled = false;
        
        if (inclureRet) {
            if (mont <= 10000) fraisRet = 100;
            else if (mont <= 50000) fraisRet = 500;
            else if (mont <= 100000) fraisRet = 800;
            else fraisRet = 0;
        }
        document.getElementById('row-comm').style.display = 'none';
        document.getElementById('row-ret').style.display = 'table-row';
    } else {
        // Autre opérateur : pas d'option frais retrait, calcul commission
        checkRetWrap.style.opacity = '0.5';
        document.getElementById('inclure_frais_retrait').checked = false;
        document.getElementById('inclure_frais_retrait').disabled = true;
        
        comm = (fraisTra * destOpObj.comm) / 100;
        document.getElementById('row-comm').style.display = 'table-row';
        document.getElementById('row-ret').style.display = 'none';
    }
    
    var totalDebite = mont + fraisTra + comm + fraisRet;
    
    document.getElementById('frais-tra').innerText = fraisTra + " Ar";
    document.getElementById('frais-comm').innerText = comm + " Ar";
    document.getElementById('frais-ret').innerText = fraisRet + " Ar";
    document.getElementById('total-debite').innerText = totalDebite.toFixed(2).replace('.', ',') + " Ar";
    
    calcBox.style.display = 'block';
}
</script>
</body>
</html>
