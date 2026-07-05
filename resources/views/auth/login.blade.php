<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAGA • Sistem Informasi Anggaran</title>

    <!-- Bootstrap Icons (optional, for backup) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Modern Color Palette - Sesuai dengan navbar */
            --primary: #6d5dfc;
            --primary-dark: #5b4bd8;
            --primary-light: #8a7dfd;
            --primary-soft: rgba(109, 93, 252, 0.1);
            --primary-glow: rgba(109, 93, 252, 0.25);

            --secondary: #22d3ee;
            --secondary-dark: #1cb5cc;
            --secondary-light: #4fddff;
            --secondary-soft: rgba(34, 211, 238, 0.1);

            --accent: #f59e0b;
            --accent-light: #fbbf24;
            --accent-soft: rgba(245, 158, 11, 0.1);

            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;

            /* Backgrounds */
            --bg-body: #f4f6fa;
            --bg-card: #ffffff;
            --bg-soft: #f9fafc;
            --bg-muted: #f3f6fc;

            /* Text Colors */
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --text-muted: #94a3b8;
            --text-inverse: #ffffff;

            /* Borders */
            --border-light: #eef2f6;
            --border-soft: #e2e8f0;
            --border-accent: #cbd5e1;

            /* Gradients - Sesuai dengan navbar */
            --gradient-primary: linear-gradient(135deg, var(--primary), var(--secondary));
            --gradient-premium: linear-gradient(135deg, var(--primary), #8b5cf6, var(--secondary));
            --gradient-glow: linear-gradient(135deg, rgba(109, 93, 252, 0.15), rgba(34, 211, 238, 0.15));

            /* Shadows */
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 35px 60px -15px rgba(109, 93, 252, 0.25);
            --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);

            /* Border Radius */
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: 500ms cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: var(--bg-body);
            color: var(--text-primary);
            line-height: 1.5;
            position: relative;
            overflow-x: hidden;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-animated {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: linear-gradient(145deg, #f4f6fa 0%, #f8fafc 50%, #eef2f6 100%);
        }

        .blob-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            opacity: 0.5;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.2;
            animation: blobFloat 20s infinite alternate ease-in-out;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            top: -200px;
            left: -200px;
            background: radial-gradient(circle at 30% 50%, var(--primary), transparent 70%);
            animation-duration: 25s;
        }

        .blob-2 {
            width: 550px;
            height: 550px;
            bottom: -250px;
            right: -200px;
            background: radial-gradient(circle at 70% 50%, var(--secondary), transparent 70%);
            animation-duration: 30s;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 400px;
            height: 400px;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle at 50% 50%, var(--accent), transparent 70%);
            animation-duration: 22s;
            animation-delay: -2s;
        }

        @keyframes blobFloat {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(40px, 40px) scale(1.1);
            }
        }

        /* ===== SUBTLE GRID ===== */
        .grid-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image:
                linear-gradient(rgba(109, 93, 252, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(109, 93, 252, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            opacity: 0.5;
        }

        /* ===== MAIN CONTAINER ===== */
        .login-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 10;
        }

        /* ===== CARD ===== */
        .login-card {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all var(--transition-base);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            z-index: 1;
        }

        .login-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-4px);
        }

        /* ===== CARD HEADER ===== */
        .card-header {
            padding: 2.5rem 2.5rem 1.75rem;
            text-align: center;
            background: linear-gradient(145deg, var(--bg-soft), var(--bg-muted));
            border-bottom: 1px solid var(--border-light);
            position: relative;
        }

        .brand-container {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .logo-wrapper {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            background: linear-gradient(145deg, white, var(--bg-soft));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
            transition: all var(--transition-bounce);
            position: relative;
            z-index: 2;
        }

        .logo-wrapper::after {
            content: '';
            position: absolute;
            inset: -4px;
            background: var(--gradient-primary);
            border-radius: 28px;
            opacity: 0;
            transition: opacity var(--transition-base);
            z-index: -1;
        }

        .logo-wrapper:hover {
            transform: scale(1.05) rotate(2deg);
            border-color: transparent;
        }

        .logo-wrapper:hover::after {
            opacity: 0.3;
        }

        .logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            transition: all var(--transition-base);
        }

        .logo-wrapper:hover .logo {
            transform: scale(1.1);
        }

        /* ===== TITLE ===== */
        .login-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            font-size: 0.95rem;
            color: var(--text-tertiary);
            font-weight: 400;
            margin-bottom: 1rem;
        }

        /* ===== BADGE ===== */
        .badge-instalasi {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1.5rem;
            background: linear-gradient(145deg, white, var(--bg-soft));
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 40px;
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-fast);
        }

        .badge-instalasi:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .badge-instalasi i {
            font-size: 0.8rem;
            color: var(--primary);
            transition: all var(--transition-fast);
        }

        .badge-instalasi:hover i {
            color: white;
            transform: rotate(360deg);
        }

        /* ===== CARD BODY ===== */
        .card-body {
            padding: 2.25rem 2.5rem 2.5rem;
        }

        /* ===== ALERT ===== */
        .alert-modern {
            background: #fef2f2;
            border-left: 5px solid var(--danger);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2rem;
            color: #b91c1c;
            font-weight: 500;
            font-size: 0.9rem;
            border: 1px solid #fee2e2;
            box-shadow: var(--shadow-sm);
        }

        .alert-modern i {
            font-size: 1.2rem;
            color: var(--danger);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            75% {
                transform: translateX(4px);
            }
        }

        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-secondary);
            letter-spacing: -0.01em;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: var(--text-muted);
            font-size: 1rem;
            z-index: 2;
            transition: all var(--transition-fast);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 1.5px solid var(--border-soft);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-primary);
            background: linear-gradient(145deg, var(--bg-card), var(--bg-soft));
            transition: all var(--transition-fast);
            height: 52px;
            box-shadow: var(--shadow-inner);
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px var(--primary-soft);
            background: white;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.9rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1rem;
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all var(--transition-fast);
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--primary);
            background: var(--primary-soft);
            transform: scale(1.1);
        }

        /* ===== CHECKBOX ===== */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            margin: 1.5rem 0 2rem;
        }

        .checkbox-custom {
            appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid var(--border-accent);
            border-radius: 7px;
            margin-right: 12px;
            position: relative;
            cursor: pointer;
            transition: all var(--transition-fast);
            background: white;
        }

        .checkbox-custom:checked {
            background: var(--gradient-primary);
            border-color: transparent;
        }

        .checkbox-custom:checked::after {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.8rem;
            color: white;
            position: absolute;
            top: 2px;
            left: 4px;
            animation: checkPop 0.3s ease;
        }

        @keyframes checkPop {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .checkbox-custom:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .checkbox-label {
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
            font-size: 0.95rem;
            transition: color var(--transition-fast);
        }

        .checkbox-wrapper:hover .checkbox-label {
            color: var(--primary);
        }

        /* ===== BUTTON ===== */
        .btn-login {
            width: 100%;
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all var(--transition-base);
            position: relative;
            overflow: hidden;
            height: 54px;
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px var(--primary-glow);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-login i {
            font-size: 1rem;
            transition: transform var(--transition-fast);
        }

        .btn-login:hover i {
            transform: translateX(6px) rotate(360deg);
        }

        /* ===== FOOTER ===== */
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-soft);
            color: var(--text-tertiary);
            font-size: 0.85rem;
        }

        .footer-copyright {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-copyright i {
            color: var(--danger);
            font-size: 0.75rem;
            animation: heartbeat 1.5s ease infinite;
        }

        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }
        }

        .footer-version {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .version-badge {
            padding: 0.25rem 0.85rem;
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            font-size: 0.7rem;
            border-radius: 30px;
            letter-spacing: 0.02em;
            box-shadow: var(--shadow-sm);
        }

        /* ===== LOADING SPINNER ===== */
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .card-header {
                padding: 2rem 1.5rem 1.5rem;
            }

            .card-body {
                padding: 1.75rem 1.5rem 2rem;
            }

            .logo-wrapper {
                width: 80px;
                height: 80px;
            }

            .logo {
                width: 48px;
                height: 48px;
            }

            .login-title {
                font-size: 2rem;
            }

            .login-footer {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-soft);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(145deg, var(--primary), var(--secondary));
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gradient-primary);
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="bg-animated"></div>
    <div class="grid-pattern"></div>
    <div class="blob-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="login-wrapper" data-aos="fade-up" data-aos-duration="800" data-aos-easing="ease-out-cubic">
        <div class="login-card">
            <!-- Card Header -->
            <div class="card-header">
                <div class="brand-container">
                    <div class="logo-wrapper">
                        <img src="{{ asset('images/logo2.png') }}" class="logo" alt="Logo SIAGA">
                    </div>
                </div>
                <h1 class="login-title">
                    <span class="gradient-text">SIAGA</span>
                </h1>
                <p class="login-subtitle">Sistem Informasi Anggaran & Akuntansi</p>
                <span class="badge-instalasi">
                    <i class="fas fa-building"></i>
                    Instalasi Gizi RSUD Kabanjahe
                </span>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                {{-- ALERT ERROR --}}
                @if ($errors->any())
                    <div class="alert-modern">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- LOGIN FORM --}}
                <form method="POST" action="{{ route('login.auth') }}" id="loginForm">
                    @csrf

                    <!-- Username Field -->
                    <div class="form-group">
                        <label class="form-label" for="username">
                            <i class="fas fa-user-circle" style="margin-right: 6px; color: var(--primary);"></i>
                            Username
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="username" id="username" class="form-control"
                                placeholder="Masukkan username" value="{{ old('username') }}" required
                                autofocus>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <i class="fas fa-lock" style="margin-right: 6px; color: var(--primary);"></i>
                            Kata Sandi
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Masukkan kata sandi" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()"
                                id="togglePasswordBtn">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember" class="checkbox-custom">
                        <label for="remember" class="checkbox-label">Ingat saya di perangkat ini</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="submitBtn">
                        <span>Masuk ke SIAGA</span>
                        <i class="fas fa-arrow-right-long"></i>
                    </button>
                </form>

                <!-- Footer -->
                <div class="login-footer">
                    <div class="footer-copyright">
                        <i class="fas fa-heart"></i>
                        <span>© <span id="currentYear"></span> Instalasi Gizi</span>
                    </div>
                    <div class="footer-version">
                        <span>v2.1.0</span>
                        <span class="version-badge">SIAGA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS with better settings
        AOS.init({
            once: true,
            mirror: false,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Set current year
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const icon = toggleBtn.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

                // Add subtle animation
                icon.style.transform = 'scale(1.2)';
                setTimeout(() => icon.style.transform = 'scale(1)', 200);
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

                // Add subtle animation
                icon.style.transform = 'scale(1.2)';
                setTimeout(() => icon.style.transform = 'scale(1)', 200);
            }
        }

        // Form submission handler with loading state
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            if (submitBtn.disabled) return;

            // Validate form
            const username = document.getElementById('username');
            const password = document.getElementById('password');

            if (!username.value.trim() || !password.value.trim()) {
                e.preventDefault();

                // Show validation error
                if (!username.value.trim()) {
                    highlightField(username, 'Username wajib diisi');
                }
                if (!password.value.trim()) {
                    highlightField(password, 'Kata sandi wajib diisi');
                }
                return;
            }

            submitBtn.disabled = true;
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <span>Memproses...</span>
                <div class="spinner"></div>
            `;

            // Allow form to submit normally
            return true;
        });

        // Field validation highlighting
        function highlightField(field, message) {
            field.style.borderColor = '#ef4444';
            field.focus();

            // Remove existing error message
            const parent = field.closest('.form-group');
            const existingMsg = parent.querySelector('.field-error');
            if (existingMsg) existingMsg.remove();

            // Add error message
            const errorMsg = document.createElement('span');
            errorMsg.className = 'field-error';
            errorMsg.style.color = '#ef4444';
            errorMsg.style.fontSize = '0.75rem';
            errorMsg.style.marginTop = '0.25rem';
            errorMsg.style.display = 'block';
            errorMsg.innerHTML = `<i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i>${message}`;
            parent.appendChild(errorMsg);

            // Remove error on input
            field.addEventListener('input', function removeError() {
                field.style.borderColor = 'var(--border-soft)';
                const msg = parent.querySelector('.field-error');
                if (msg) msg.remove();
                field.removeEventListener('input', removeError);
            }, {
                once: true
            });
        }

        // Real-time validation styling
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('invalid', (e) => {
                e.preventDefault();
                const message = input.id === 'username' ? 'Username wajib diisi' : 'Kata sandi wajib diisi';
                highlightField(input, message);
            });
        });

        // Add subtle focus effects
        const formControls = document.querySelectorAll('.form-control');
        formControls.forEach(control => {
            control.addEventListener('focus', () => {
                const icon = control.closest('.input-wrapper')?.querySelector('.input-icon');
                if (icon) {
                    icon.style.color = 'var(--primary)';
                    icon.style.transform = 'scale(1.1)';
                }
            });

            control.addEventListener('blur', () => {
                const icon = control.closest('.input-wrapper')?.querySelector('.input-icon');
                if (icon) {
                    icon.style.color = 'var(--text-muted)';
                    icon.style.transform = 'scale(1)';
                }
            });
        });

        // Log success message (for debugging)
        console.log('✨ SIAGA Login Page - Designed with #6d5dfc and #22d3ee');
    </script>
</body>

</html>
