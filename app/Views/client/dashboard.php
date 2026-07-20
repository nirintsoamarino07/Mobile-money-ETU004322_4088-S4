<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Client - Send Vola</title>
    <meta name="description" content="Tableau de bord Send Vola – Gérez vos dépôts, retraits et transferts d'argent mobile en toute simplicité.">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --primary-lighter: #93c5fd;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-page: #eef2f7;
            --bg-sidebar: #0f172a;
            --sidebar-width: 260px;
            --text-sidebar: #94a3b8;
            --text-sidebar-active: #ffffff;
            --card-radius: 1.25rem;
            --card-shadow: 0 4px 24px rgba(37, 99, 235, 0.08);
            --transition: 0.25s ease;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-page);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ─────────────────────────────── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            box-shadow: 4px 0 24px rgba(0,0,0,0.18);
            transition: transform var(--transition);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 28px 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
        }
        .sidebar-brand-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: white;
            letter-spacing: -0.02em;
        }
        .sidebar-brand-sub {
            font-size: 0.7rem;
            color: var(--text-sidebar);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .sidebar-section-label {
            padding: 18px 22px 6px;
            font-size: 0.68rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #475569;
            font-weight: 600;
        }

        .sidebar-nav { flex: 1; padding: 4px 0; overflow-y: auto; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 22px;
            color: var(--text-sidebar);
            text-decoration: none;
            border-radius: 0;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background var(--transition), color var(--transition);
            border-left: 3px solid transparent;
            margin: 1px 0;
        }
        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.05);
            color: #cbd5e1;
        }
        .sidebar-nav a.active {
            background: rgba(37, 99, 235, 0.18);
            color: white;
            border-left-color: var(--primary-light);
        }
        .sidebar-nav a i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        .sidebar-nav a .badge-side {
            margin-left: auto;
            background: var(--primary);
            color: white;
            border-radius: 20px;
            font-size: 0.65rem;
            padding: 2px 8px;
            font-weight: 700;
        }

        /* SIDEBAR BOTTOM CARD */


        .sidebar-bottom-links {
            padding: 0 0 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        /* ── MAIN CONTENT ─────────────────────────── */
        #main {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 32px 32px 40px;
            min-height: 100vh;
        }

        /* TOP BAR */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .topbar-welcome h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
        }
        .topbar-welcome p {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 2px;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-phone {
            background: white;
            border-radius: 50px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-phone i { font-size: 1rem; }

        /* FLASH MESSAGES */
        .flash-toast {
            position: fixed;
            top: 22px; right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .flash-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            max-width: 380px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            animation: slideIn 0.4s ease;
        }
        .flash-item.success { background: #ecfdf5; color: #065f46; border-left: 4px solid var(--success); }
        .flash-item.error   { background: #fef2f2; color: #991b1b; border-left: 4px solid var(--danger); }
        .flash-item i { font-size: 1.1rem; flex-shrink: 0; }
        @keyframes slideIn {
            from { transform: translateX(120px); opacity: 0; }
            to   { transform: translateX(0);     opacity: 1; }
        }

        /* TAB SECTIONS */
        .tab-section { display: none; }
        .tab-section.active { display: block; animation: fadeUp 0.3s ease; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── CARDS ─────────────────────────────────── */
        .card {
            background: white;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            border: none;
        }
        .card-body { padding: 22px 24px; }

        /* Balance Card */
        .balance-card {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            border-radius: var(--card-radius);
            padding: 28px 28px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(37, 99, 235, 0.35);
        }
        .balance-card::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .balance-card::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 30px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .balance-label {
            font-size: 0.8rem;
            font-weight: 600;
            opacity: 0.75;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .balance-amount {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 4px;
        }
        .balance-unit {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.75;
        }
        .balance-phone-line {
            margin-top: 18px;
            font-size: 0.8rem;
            opacity: 0.7;
        }
        .balance-phone-line strong { opacity: 1; font-weight: 700; }

        /* Stat mini cards */
        .stat-card {
            background: white;
            border-radius: var(--card-radius);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--card-shadow);
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(37,99,235,0.12);
        }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: rgba(37,99,235,0.1); color: var(--primary); }
        .stat-icon.green  { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-icon.orange { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.red    { background: rgba(239,68,68,0.1); color: var(--danger); }
        .stat-label {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
        }

        /* Section Title */
        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title i { color: var(--primary); }

        /* ── QUICK ACTION BUTTONS ─────────────────── */
        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 12px;
            background: white;
            border-radius: 14px;
            text-decoration: none;
            color: #1e293b;
            box-shadow: var(--card-shadow);
            cursor: pointer;
            border: 2px solid transparent;
            transition: all var(--transition);
            flex: 1;
            min-width: 100px;
        }
        .quick-btn:hover, .quick-btn:focus {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.15);
            color: var(--primary);
        }
        .quick-btn i { font-size: 1.5rem; }
        .quick-btn span { font-size: 0.78rem; font-weight: 600; }

        /* ── FORMS ────────────────────────────────── */
        .form-card {
            background: white;
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            padding: 30px;
            max-width: 520px;
        }
        .form-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .form-card-subtitle {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 24px;
        }
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }
        .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
            outline: none;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37,99,235,0.4);
        }
        .amount-shortcuts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .amount-btn {
            background: rgba(37,99,235,0.07);
            color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .amount-btn:hover { background: rgba(37,99,235,0.15); }

        .fee-preview {
            background: #f8faff;
            border: 1px solid #e0e8ff;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 14px;
            font-size: 0.83rem;
            color: #475569;
        }
        .fee-preview .fee-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .fee-preview .fee-row:last-child { margin-bottom: 0; border-top: 1px dashed #c7d7fd; padding-top: 6px; margin-top: 4px; }
        .fee-preview .fee-total { font-weight: 700; color: var(--primary); font-size: 0.9rem; }

        /* ── HISTORY TABLE ────────────────────────── */
        .history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
        }
        .history-table thead th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            font-weight: 600;
            padding: 0 16px 10px;
        }
        .history-table tbody tr {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .history-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(37,99,235,0.08); }
        .history-table td {
            padding: 14px 16px;
            font-size: 0.875rem;
            vertical-align: middle;
        }
        .history-table td:first-child { border-radius: 12px 0 0 12px; }
        .history-table td:last-child  { border-radius: 0 12px 12px 0; }
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .type-badge.dep  { background: rgba(16,185,129,0.1); color: #065f46; }
        .type-badge.ret  { background: rgba(239,68,68,0.1);  color: #991b1b; }
        .type-badge.tra  { background: rgba(37,99,235,0.1);  color: #1e40af; }
        .amount-pos { color: var(--success); font-weight: 700; }
        .amount-neg { color: var(--danger);  font-weight: 700; }

        /* ── CHART ────────────────────────────────── */
        .chart-wrap { position: relative; height: 240px; }

        /* ── EMPTY STATE ──────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }
        .empty-state i { font-size: 3rem; color: #cbd5e1; margin-bottom: 12px; display: block; }
        .empty-state p  { color: #94a3b8; font-size: 0.9rem; }

        /* ── RESPONSIVE ───────────────────────────── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main { margin-left: 0; padding: 20px 16px; }
            .balance-amount { font-size: 1.8rem; }
        }

        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #0f172a;
            cursor: pointer;
        }
        @media (max-width: 768px) { .hamburger { display: block; } }
    </style>
</head>
<body>

<?php
// ── PHP Data Preparation ───────────────────────────────────────
$telephone = esc($client['telephone']);
$solde     = $client['solde'];

// Statistics from history
$totalDep = 0; $totalRet = 0; $totalTra = 0;
$countDep = 0; $countRet = 0; $countTra = 0;
foreach ($history as $h) {
    if ($h['type_code'] === 'DEP')      { $totalDep += $h['montant']; $countDep++; }
    elseif ($h['type_code'] === 'RET') { $totalRet += $h['montant']; $countRet++; }
    elseif ($h['type_code'] === 'TRA') { $totalTra += $h['montant']; $countTra++; }
}

// Barème statique pour le calculateur de frais JS
$baremesRET = [
    ['min'=>0,     'max'=>10000,  'fixe'=>100,  'pct'=>0],
    ['min'=>10001, 'max'=>50000,  'fixe'=>500,  'pct'=>0],
    ['min'=>50001, 'max'=>100000, 'fixe'=>1000, 'pct'=>0],
];
$baremesTRA = [
    ['min'=>0,     'max'=>10000,  'fixe'=>200,  'pct'=>0],
    ['min'=>10001, 'max'=>50000,  'fixe'=>700,  'pct'=>0],
    ['min'=>50001, 'max'=>100000, 'fixe'=>1500, 'pct'=>0],
];
$baremesRET_json = json_encode($baremesRET);
$baremesTRA_json = json_encode($baremesTRA);
?>

<!-- ── FLASH TOASTS ───────────────────────────────────────── -->
<div class="flash-toast" id="flashContainer">
<?php if (session()->getFlashdata('success')): ?>
    <div class="flash-item success">
        <i class="bi bi-check-circle-fill"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="flash-item error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>
</div>

<!-- ── SIDEBAR ───────────────────────────────────────────── -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="bi bi-send-fill"></i></div>
        <div>
            <div class="sidebar-brand-name">Send Vola</div>
            <div class="sidebar-brand-sub">Mobile Money</div>
        </div>
    </div>

    <div class="sidebar-section-label">Menu</div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-link-tab active" data-tab="tab-home">
            <i class="bi bi-grid-fill"></i> Tableau de bord
        </a>
        <a href="#" class="nav-link-tab" data-tab="tab-depot">
            <i class="bi bi-arrow-down-circle-fill"></i> Dépôt
        </a>
        <a href="#" class="nav-link-tab" data-tab="tab-retrait">
            <i class="bi bi-arrow-up-circle-fill"></i> Retrait
        </a>
        <a href="#" class="nav-link-tab" data-tab="tab-transfert">
            <i class="bi bi-arrow-left-right"></i> Transfert
        </a>
        <a href="#" class="nav-link-tab" data-tab="tab-history">
            <i class="bi bi-clock-history"></i> Historique
            <?php if (count($history) > 0): ?>
            <span class="badge-side"><?= count($history) ?></span>
            <?php endif; ?>
        </a>
    </nav>

    <div class="sidebar-section-label">Outils</div>
    <nav class="sidebar-nav">
        <a href="<?= site_url('operator') ?>">
            <i class="bi bi-building"></i> Espace Opérateur
        </a>
        <a href="<?= site_url('client/logout') ?>" style="color:#f87171;">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>
    </nav>


</nav>

<!-- ── MAIN ──────────────────────────────────────────────── -->
<main id="main">

    <!-- TOP BAR -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger" id="sidebarToggle"><i class="bi bi-list"></i></button>
            <div class="topbar-welcome">
                <h1 id="pageTitle">Tableau de bord</h1>
                <p id="pageDate"></p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-phone">
                <i class="bi bi-phone-fill"></i>
                <?= $telephone ?>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════ -->
    <!-- TAB: HOME / DASHBOARD                        -->
    <!-- ════════════════════════════════════════════ -->
    <div id="tab-home" class="tab-section active">

        <div class="row g-4">
            <!-- Balance Card -->
            <div class="col-12 col-md-5">
                <div class="balance-card h-100">
                    <div class="balance-label"><i class="bi bi-wallet2"></i> &nbsp;Mon Solde</div>
                    <div class="balance-amount">
                        <?= number_format($solde, 0, ',', ' ') ?>
                    </div>
                    <div class="balance-unit">Ariary Malgache (Ar)</div>
                    <div class="balance-phone-line">
                        <i class="bi bi-phone"></i>&nbsp;
                        <strong><?= $telephone ?></strong>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="col-12 col-md-7">
                <div class="row g-3 h-100">
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon green"><i class="bi bi-arrow-down-circle-fill"></i></div>
                            <div>
                                <div class="stat-label">Total Dépôts</div>
                                <div class="stat-value"><?= number_format($totalDep, 0, ',', ' ') ?> Ar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon red"><i class="bi bi-arrow-up-circle-fill"></i></div>
                            <div>
                                <div class="stat-label">Total Retraits</div>
                                <div class="stat-value"><?= number_format($totalRet, 0, ',', ' ') ?> Ar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon blue"><i class="bi bi-arrow-left-right"></i></div>
                            <div>
                                <div class="stat-label">Total Transferts</div>
                                <div class="stat-value"><?= number_format($totalTra, 0, ',', ' ') ?> Ar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card h-100">
                            <div class="stat-icon orange"><i class="bi bi-list-ul"></i></div>
                            <div>
                                <div class="stat-label">Opérations</div>
                                <div class="stat-value"><?= count($history) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-4">
            <div class="section-title"><i class="bi bi-lightning-charge-fill"></i> Actions rapides</div>
            <div class="d-flex gap-3 flex-wrap">
                <button class="quick-btn nav-link-tab" data-tab="tab-depot">
                    <i class="bi bi-arrow-down-circle-fill" style="color:#10b981;"></i>
                    <span>Dépôt</span>
                </button>
                <button class="quick-btn nav-link-tab" data-tab="tab-retrait">
                    <i class="bi bi-arrow-up-circle-fill" style="color:#ef4444;"></i>
                    <span>Retrait</span>
                </button>
                <button class="quick-btn nav-link-tab" data-tab="tab-transfert">
                    <i class="bi bi-arrow-left-right" style="color:#2563eb;"></i>
                    <span>Transfert</span>
                </button>
                <button class="quick-btn nav-link-tab" data-tab="tab-history">
                    <i class="bi bi-clock-history" style="color:#f59e0b;"></i>
                    <span>Historique</span>
                </button>
            </div>
        </div>

        <!-- Chart -->
        <div class="mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="section-title"><i class="bi bi-bar-chart-fill"></i> Activité des opérations</div>
                    <div class="chart-wrap">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent History -->
        <?php if (!empty($history)): ?>
        <div class="mt-4">
            <div class="section-title"><i class="bi bi-clock-history"></i> Transactions récentes</div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive p-3">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Détails</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach(array_slice($history, 0, 5) as $h): ?>
                                <tr>
                                    <td>
                                        <?php if ($h['type_code'] === 'DEP'): ?>
                                            <span class="type-badge dep"><i class="bi bi-arrow-down"></i> Dépôt</span>
                                        <?php elseif ($h['type_code'] === 'RET'): ?>
                                            <span class="type-badge ret"><i class="bi bi-arrow-up"></i> Retrait</span>
                                        <?php else: ?>
                                            <span class="type-badge tra"><i class="bi bi-arrow-left-right"></i> Transfert</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="color:#64748b; font-size:0.82rem;">
                                        <?php if ($h['type_code'] === 'DEP'): ?>
                                            Reçu sur mon compte
                                        <?php elseif ($h['type_code'] === 'RET'): ?>
                                            Retiré de mon compte
                                        <?php else: ?>
                                            <?php if ($h['client_id_expediteur'] == $client['id']): ?>
                                                → <strong><?= esc($h['destinataire_tel']) ?></strong>
                                            <?php else: ?>
                                                ← <strong><?= esc($h['expediteur_tel']) ?></strong>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $sign = '';
                                            if ($h['type_code'] === 'DEP') $sign = '+';
                                            elseif ($h['type_code'] === 'RET') $sign = '-';
                                            else $sign = ($h['client_id_expediteur'] == $client['id']) ? '-' : '+';
                                        ?>
                                        <span class="<?= $sign === '+' ? 'amount-pos' : 'amount-neg' ?>">
                                            <?= $sign ?><?= number_format($h['montant'], 0, ',', ' ') ?> Ar
                                        </span>
                                    </td>
                                    <td style="color:#94a3b8; font-size:0.8rem;"><?= esc($h['date_transaction']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- ════════════════════════════════════════════ -->
    <!-- TAB: DEPOT                                   -->
    <!-- ════════════════════════════════════════════ -->
    <div id="tab-depot" class="tab-section">
        <div class="form-card">
            <div class="form-card-title"><i class="bi bi-arrow-down-circle-fill" style="color:#10b981;"></i> Dépôt d'argent</div>
            <div class="form-card-subtitle">Déposez de l'argent sur votre compte instantanément.</div>

            <form action="<?= site_url('client/deposit') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="dep_amount" class="form-label">Montant à déposer (Ar)</label>
                    <input type="number" step="1" id="dep_amount" name="amount" class="form-control"
                           placeholder="ex : 50 000" required min="1">
                    <div class="amount-shortcuts mt-2">
                        <button type="button" class="amount-btn" onclick="setAmount('dep_amount', 5000)">5 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('dep_amount', 10000)">10 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('dep_amount', 20000)">20 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('dep_amount', 50000)">50 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('dep_amount', 100000)">100 000</button>
                    </div>
                </div>
                <div class="fee-preview">
                    <div class="fee-row"><span>Frais de dépôt :</span><span>0 Ar (Gratuit)</span></div>
                    <div class="fee-row"><span class="fee-total">Vous recevez :</span><span class="fee-total" id="dep_receive">— Ar</span></div>
                </div>
                <button type="submit" class="btn-primary-custom mt-4 w-100 justify-content-center">
                    <i class="bi bi-check-circle-fill"></i> Confirmer le dépôt
                </button>
            </form>
        </div>
    </div>

    <!-- ════════════════════════════════════════════ -->
    <!-- TAB: RETRAIT                                 -->
    <!-- ════════════════════════════════════════════ -->
    <div id="tab-retrait" class="tab-section">
        <div class="form-card">
            <div class="form-card-title"><i class="bi bi-arrow-up-circle-fill" style="color:#ef4444;"></i> Retrait d'argent</div>
            <div class="form-card-subtitle">Retirez de l'argent de votre compte. Les frais s'appliquent selon le barème.</div>

            <form action="<?= site_url('client/withdraw') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="wit_amount" class="form-label">Montant à retirer (Ar)</label>
                    <input type="number" step="1" id="wit_amount" name="amount" class="form-control"
                           placeholder="ex : 10 000" required min="1" oninput="calcFee(this.value, 'RET')">
                    <div class="amount-shortcuts mt-2">
                        <button type="button" class="amount-btn" onclick="setAmount('wit_amount',5000);calcFee(5000,'RET')">5 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('wit_amount',10000);calcFee(10000,'RET')">10 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('wit_amount',20000);calcFee(20000,'RET')">20 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('wit_amount',50000);calcFee(50000,'RET')">50 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('wit_amount',100000);calcFee(100000,'RET')">100 000</button>
                    </div>
                </div>
                <div class="fee-preview" id="ret_preview">
                    <div class="fee-row"><span>Montant retiré :</span><span id="ret_montant">— Ar</span></div>
                    <div class="fee-row"><span>Frais de retrait :</span><span id="ret_frais">— Ar</span></div>
                    <div class="fee-row"><span class="fee-total">Total débité :</span><span class="fee-total" id="ret_total">— Ar</span></div>
                </div>
                <button type="submit" class="btn-primary-custom mt-4 w-100 justify-content-center" style="background: linear-gradient(135deg,#b91c1c,#ef4444);">
                    <i class="bi bi-check-circle-fill"></i> Confirmer le retrait
                </button>
            </form>
        </div>
    </div>

    <!-- ════════════════════════════════════════════ -->
    <!-- TAB: TRANSFERT                               -->
    <!-- ════════════════════════════════════════════ -->
    <div id="tab-transfert" class="tab-section">
        <div class="form-card">
            <div class="form-card-title"><i class="bi bi-arrow-left-right" style="color:#2563eb;"></i> Transfert d'argent</div>
            <div class="form-card-subtitle">Envoyez de l'argent vers un autre numéro. Les frais s'appliquent selon le barème.</div>

            <form action="<?= site_url('client/transfer') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="dest_tel" class="form-label">Numéro du destinataire</label>
                    <input type="text" id="dest_tel" name="telephone_dest" class="form-control"
                           placeholder="ex : 0337654321" required>
                </div>
                <div class="mb-3">
                    <label for="tra_amount" class="form-label">Montant à envoyer (Ar)</label>
                    <input type="number" step="1" id="tra_amount" name="amount" class="form-control"
                           placeholder="ex : 20 000" required min="1" oninput="calcFee(this.value, 'TRA')">
                    <div class="amount-shortcuts mt-2">
                        <button type="button" class="amount-btn" onclick="setAmount('tra_amount',5000);calcFee(5000,'TRA')">5 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('tra_amount',10000);calcFee(10000,'TRA')">10 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('tra_amount',20000);calcFee(20000,'TRA')">20 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('tra_amount',50000);calcFee(50000,'TRA')">50 000</button>
                        <button type="button" class="amount-btn" onclick="setAmount('tra_amount',100000);calcFee(100000,'TRA')">100 000</button>
                    </div>
                </div>
                <div class="fee-preview" id="tra_preview">
                    <div class="fee-row"><span>Montant envoyé :</span><span id="tra_montant">— Ar</span></div>
                    <div class="fee-row"><span>Frais de transfert :</span><span id="tra_frais">— Ar</span></div>
                    <div class="fee-row"><span class="fee-total">Total débité :</span><span class="fee-total" id="tra_total">— Ar</span></div>
                </div>
                <button type="submit" class="btn-primary-custom mt-4 w-100 justify-content-center">
                    <i class="bi bi-send-fill"></i> Envoyer le transfert
                </button>
            </form>
        </div>
    </div>

    <!-- ════════════════════════════════════════════ -->
    <!-- TAB: HISTORY                                 -->
    <!-- ════════════════════════════════════════════ -->
    <div id="tab-history" class="tab-section">
        <div class="section-title"><i class="bi bi-clock-history"></i> Historique complet</div>

        <?php if (empty($history)): ?>
            <div class="card"><div class="card-body empty-state">
                <i class="bi bi-inbox"></i>
                <p>Aucune transaction enregistrée.</p>
            </div></div>
        <?php else: ?>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Détails</th>
                                <th>Montant</th>
                                <th>Frais</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($history as $h): ?>
                            <?php
                                $sign = '';
                                if ($h['type_code'] === 'DEP') $sign = '+';
                                elseif ($h['type_code'] === 'RET') $sign = '-';
                                else $sign = ($h['client_id_expediteur'] == $client['id']) ? '-' : '+';
                            ?>
                            <tr>
                                <td>
                                    <?php if ($h['type_code'] === 'DEP'): ?>
                                        <span class="type-badge dep"><i class="bi bi-arrow-down"></i> Dépôt</span>
                                    <?php elseif ($h['type_code'] === 'RET'): ?>
                                        <span class="type-badge ret"><i class="bi bi-arrow-up"></i> Retrait</span>
                                    <?php else: ?>
                                        <span class="type-badge tra"><i class="bi bi-arrow-left-right"></i> Transfert</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color:#64748b; font-size:0.82rem;">
                                    <?php if ($h['type_code'] === 'DEP'): ?>
                                        Reçu sur mon compte
                                    <?php elseif ($h['type_code'] === 'RET'): ?>
                                        Retiré de mon compte
                                    <?php else: ?>
                                        <?php if ($h['client_id_expediteur'] == $client['id']): ?>
                                            → <strong><?= esc($h['destinataire_tel']) ?></strong>
                                        <?php else: ?>
                                            ← <strong><?= esc($h['expediteur_tel']) ?></strong>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?= $sign === '+' ? 'amount-pos' : 'amount-neg' ?>">
                                        <?= $sign ?><?= number_format($h['montant'], 0, ',', ' ') ?> Ar
                                    </span>
                                </td>
                                <td style="color:#94a3b8; font-size:0.82rem;">
                                    <?php if ($h['client_id_expediteur'] == $client['id'] && $h['frais'] > 0): ?>
                                        <?= number_format($h['frais'], 0, ',', ' ') ?> Ar
                                    <?php else: ?>
                                        0 Ar
                                    <?php endif; ?>
                                </td>
                                <td style="color:#94a3b8; font-size:0.8rem;"><?= esc($h['date_transaction']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

</main>

<!-- ── SCRIPTS ────────────────────────────────────────────── -->
<script>
// ── BARÈME DATA from PHP ─────────────────────────────────
const baremesRET = <?= $baremesRET_json ?>;
const baremesTRA = <?= $baremesTRA_json ?>;

function fmt(n) {
    return new Intl.NumberFormat('fr-MG').format(Math.round(n)) + ' Ar';
}

function getFee(amount, type) {
    const baremes = type === 'RET' ? baremesRET : baremesTRA;
    for (const b of baremes) {
        if (amount >= b.min && amount <= b.max) {
            return b.fixe + (amount * b.pct / 100);
        }
    }
    return 0;
}

function calcFee(amount, type) {
    amount = parseFloat(amount) || 0;
    const fee = getFee(amount, type);
    const total = amount + fee;
    if (type === 'RET') {
        document.getElementById('ret_montant').textContent = fmt(amount);
        document.getElementById('ret_frais').textContent   = fmt(fee);
        document.getElementById('ret_total').textContent   = fmt(total);
    } else {
        document.getElementById('tra_montant').textContent = fmt(amount);
        document.getElementById('tra_frais').textContent   = fmt(fee);
        document.getElementById('tra_total').textContent   = fmt(total);
    }
}

function setAmount(id, val) {
    document.getElementById(id).value = val;
    if (id === 'dep_amount') {
        document.getElementById('dep_receive').textContent = fmt(val);
    }
}

document.getElementById('dep_amount').addEventListener('input', function() {
    document.getElementById('dep_receive').textContent = this.value ? fmt(parseFloat(this.value)) : '— Ar';
});

// ── TAB NAVIGATION ──────────────────────────────────────
const pageTitles = {
    'tab-home':      'Tableau de bord',
    'tab-depot':     'Dépôt d\'argent',
    'tab-retrait':   'Retrait d\'argent',
    'tab-transfert': 'Transfert d\'argent',
    'tab-history':   'Historique'
};
document.querySelectorAll('.nav-link-tab').forEach(el => {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.dataset.tab;
        // Update sidebar active
        document.querySelectorAll('.sidebar-nav a.nav-link-tab').forEach(a => a.classList.remove('active'));
        document.querySelectorAll(`.sidebar-nav a[data-tab="${target}"]`).forEach(a => a.classList.add('active'));
        // Update sections
        document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
        document.getElementById(target).classList.add('active');
        // Update title
        document.getElementById('pageTitle').textContent = pageTitles[target] || '';
        // Close sidebar on mobile
        document.getElementById('sidebar').classList.remove('open');
    });
});

// ── HAMBURGER ────────────────────────────────────────────
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('open');
});

// ── DATE DISPLAY ─────────────────────────────────────────
(function() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('pageDate').textContent = now.toLocaleDateString('fr-MG', options);
})();

// ── CHART ────────────────────────────────────────────────
window.addEventListener('load', function() {
    const canvas = document.getElementById('activityChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Dépôts', 'Retraits', 'Transferts'],
            datasets: [{
                label: 'Nombre d\'opérations',
                data: [<?= (int)$countDep ?>, <?= (int)$countRet ?>, <?= (int)$countTra ?>],
                backgroundColor: [
                    'rgba(16,185,129,0.8)',
                    'rgba(239,68,68,0.8)',
                    'rgba(37,99,235,0.8)'
                ],
                borderRadius: 10,
                borderSkipped: false,
                barPercentage: 0.55,
            }, {
                label: 'Montant total (en milliers Ar)',
                data: [<?= round($totalDep/1000, 1) ?>, <?= round($totalRet/1000, 1) ?>, <?= round($totalTra/1000, 1) ?>],
                backgroundColor: [
                    'rgba(16,185,129,0.2)',
                    'rgba(239,68,68,0.2)',
                    'rgba(37,99,235,0.2)'
                ],
                borderRadius: 10,
                borderSkipped: false,
                barPercentage: 0.55,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 800, easing: 'easeOutQuart' },
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'Inter', size: 12 }, color: '#64748b', boxRadius: 6 } },
                tooltip: { mode: 'index', intersect: false, backgroundColor: '#1e293b', titleFont: { family: 'Inter' }, bodyFont: { family: 'Inter' } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: '600' }, color: '#94a3b8' } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'Inter' }, color: '#94a3b8' } }
            }
        }
    });
});

// ── AUTO-HIDE FLASH ──────────────────────────────────────
(function() {
    const container = document.getElementById('flashContainer');
    if (container && container.children.length > 0) {
        setTimeout(() => {
            container.style.transition = 'opacity 0.5s';
            container.style.opacity = '0';
            setTimeout(() => container.remove(), 500);
        }, 5000);
    }
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
