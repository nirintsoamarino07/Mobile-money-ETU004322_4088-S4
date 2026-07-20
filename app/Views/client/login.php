<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client – Send Vola</title>
    <meta name="description" content="Connectez-vous à votre espace client Send Vola pour gérer vos opérations Mobile Money.">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --accent: #06b6d4;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            background: #0f172a;
        }

        /* ── LEFT PANEL ──────────────────────────── */
        .login-left {
            flex: 1;
            background:
                linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(37,99,235,0.55) 100%),
                url('<?= base_url('src/Money.jpg') ?>') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 350px; height: 350px;
            background: rgba(37,99,235,0.15);
            border-radius: 50%;
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -40px;
            width: 280px; height: 280px;
            background: rgba(6,182,212,0.1);
            border-radius: 50%;
        }
        .left-content { position: relative; z-index: 1; }
        .brand-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 60px;
        }
        .brand-icon {
            width: 50px; height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            color: white;
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
        }
        .brand-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .left-headline {
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            font-weight: 800;
            color: white;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 18px;
        }
        .left-headline span {
            background: linear-gradient(90deg, #93c5fd, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .left-desc {
            font-size: 1rem;
            color: rgba(255,255,255,0.65);
            line-height: 1.65;
            max-width: 400px;
            margin-bottom: 40px;
        }
        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.75);
            font-size: 0.88rem;
            font-weight: 500;
        }
        .feature-list .fi {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* ── RIGHT PANEL ─────────────────────────── */
        .login-right {
            width: 440px;
            background: #f8faff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 42px;
        }

        .login-card-header {
            margin-bottom: 30px;
        }
        .login-card-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
            margin-bottom: 6px;
        }
        .login-card-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 7px;
        }
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
        }
        .form-control {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .form-control::placeholder { color: #cbd5e1; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
            margin-bottom: 16px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(37,99,235,0.45);
        }
        .btn-login:active { transform: translateY(0); }

        .btn-operator {
            width: 100%;
            padding: 11px;
            background: transparent;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-operator:hover { border-color: var(--primary); color: var(--primary); }

        /* Flash alerts */
        .alert-custom {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-error   { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #cbd5e1;
            font-size: 0.78rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        } */

        /* .login-footer {
            text-align: center;
            margin-top: 28px;
            color: #94a3b8;
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .login-left { flex: none; min-height: 240px; padding: 36px 28px; }
            .left-headline { font-size: 1.5rem; }
            .left-desc, .feature-list { display: none; }
            .login-right { width: 100%; padding: 36px 24px; }
        }
    </style>
</head>
<body>

<!-- ── LEFT PANEL ─────────────────────────────────── -->
<div class="login-left">
    <div class="left-content">
        <div class="brand-row">
            <div class="brand-icon"><i class="bi bi-send-fill"></i></div>
            <div>
                <div class="brand-name">Send Vola</div>
                <div class="brand-sub">Mobile Money</div>
            </div>
        </div>
        <h1 class="left-headline">Votre argent,<br><span>partout, à tout moment.</span></h1>
        <p class="left-desc">
            Déposez, retirez et transférez de l'argent en quelques secondes,
            directement depuis votre espace client sécurisé.
        </p>

    </div>
</div>

<!-- ── RIGHT PANEL ────────────────────────────────── -->
<div class="login-right">

    <div class="login-card-header">
        <div class="login-card-title">Tongasoa</div>
        <div class="login-card-subtitle">Connectez-vous avec votre numéro de téléphone.</div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert-custom alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert-custom alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?= site_url('client/login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="telephone" class="form-label">Numéro de téléphone</label>
            <div class="input-wrap">
                <i class="bi bi-phone-fill input-icon"></i>
                <input type="text"
                       id="telephone"
                       name="telephone"
                       class="form-control"
                       placeholder="ex : 0331234567"
                       autocomplete="tel"
                       required>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i>
            Se connecter
        </button>
    </form>

    <div class="divider">ou</div>

    <a href="<?= site_url('operator') ?>" class="btn-operator">
        <i class="bi bi-building"></i>
        Accéder à l'Espace Opérateur
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
