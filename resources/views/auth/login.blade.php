<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — PowerStock</title>

    {{-- Favicons --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon_io/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon_io/apple-touch-icon.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Tabler Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           VARIABLES CSS (thème clair / sombre)
           Même clé 'ps_theme' que votre layout super_admin
        ═══════════════════════════════════════════ */
        :root,
        [data-theme="light"] {
            --bg-page    : #f4f6f9;
            --bg-card    : #ffffff;
            --text-primary : #1a1a2e;
            --text-secondary : #64748b;
            --text-muted : #94a3b8;
            --border-color : #eaecf0;
            --accent     : #f97316;
            --accent-dark: #ea580c;
            --accent-light: #fff7ed;
            --input-bg   : #f8fafc;
            --input-border: #e2e8f0;
            --shadow     : 0 8px 30px rgba(0,0,0,0.06);
        }

        [data-theme="dark"] {
            --bg-page    : #0f1117;
            --bg-card    : #1a1d27;
            --text-primary : #e2e8f0;
            --text-secondary : #94a3b8;
            --text-muted : #64748b;
            --border-color : #2d3348;
            --accent     : #f97316;
            --accent-dark: #ea580c;
            --accent-light: #2d2318;
            --input-bg   : #1e2130;
            --input-border: #2d3348;
            --shadow     : 0 8px 30px rgba(0,0,0,0.4);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.25s, color 0.25s;
            padding: 20px;
        }

        /* ── Thème toggle (discret, en haut à droite) ── */
        .theme-toggle {
            position: fixed;
            top: 16px;
            right: 16px;
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 18px;
            z-index: 10;
            transition: background 0.15s;
        }
        .theme-toggle:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        /* ── Carte de connexion ── */
        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── En-tête de la carte ── */
        .login-header {
            padding: 32px 30px 24px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .brand {
            display: flex; align-items: center; justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800;
            color: #fff;
        }
        .brand-name {
            font-size: 22px; font-weight: 700;
            color: var(--text-primary);
        }
        .brand-sub {
            font-size: 12px; color: var(--text-muted);
            margin-top: 2px;
        }

        .login-header h2 {
            font-size: 18px; font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        .login-header p {
            font-size: 13px; color: var(--text-muted);
            margin: 0;
        }

        /* ── Corps du formulaire ── */
        .login-body {
            padding: 28px 30px 32px;
        }

        .form-label {
            font-size: 13px; font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .input-group-text {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-muted);
            font-size: 16px;
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--text-primary);
            font-size: 14px;
            padding: 10px 14px;
            border-radius: 0 8px 8px 0;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
            background: var(--input-bg);
            color: var(--text-primary);
        }

        .btn-login {
            background: var(--accent);
            border: none;
            color: #fff;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-login:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        /* Message d'erreur */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-error i { color: #ef4444; font-size: 18px; }

        /* Pied de carte */
        .login-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            padding: 16px 30px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    {{-- Thème toggle (clair/sombre) --}}
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Changer le thème">
        <i class="ti ti-moon" id="themeIcon"></i>
    </button>

    {{-- Carte de connexion --}}
    <div class="login-card">
        <div class="login-header">
            <div class="brand">
                <div class="brand-icon">PS</div>
                <div>
                    <div class="brand-name">PowerStock</div>
                    <div class="brand-sub">Gestion de magasin</div>
                </div>
            </div>
            <h2>Connexion</h2>
            <p>Accédez à votre espace d’administration</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert-error">
                    <i class="ti ti-alert-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Nom d’utilisateur ou email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-user"></i></span>
                        <input type="text" name="username" id="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" placeholder="super_admin" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-lock"></i></span>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="ti ti-login me-2"></i> Se connecter
                </button>
            </form>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} PowerStock — Tous droits réservés
        </div>
    </div>

    <script>
        /* ═══════════════════════════════════════════
           GESTION DU THÈME (identique au layout)
           Utilise la même clé localStorage 'ps_theme'
        ═══════════════════════════════════════════ */
        const html = document.documentElement;
        const themeIcon = document.getElementById('themeIcon');

        function getSavedTheme() {
            const saved = localStorage.getItem('ps_theme');
            if (saved) return saved;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            themeIcon.className = theme === 'dark' ? 'ti ti-sun' : 'ti ti-moon';
            localStorage.setItem('ps_theme', theme);
        }

        function toggleTheme() {
            const current = html.getAttribute('data-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        }

        // Initialisation
        applyTheme(getSavedTheme());
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
