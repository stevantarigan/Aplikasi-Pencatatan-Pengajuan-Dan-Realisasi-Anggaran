<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Anggaran - Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Animated Particles -->
    <div class="particles" id="particles"></div>

    <!-- Login Container -->
    <div class="login-container">
        <!-- Decorative accent line -->
        <div class="accent-line"></div>

        <!-- Header -->
        <div class="login-header">
            <div class="logo-container">
                <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="logo">
            </div>
            <h1 class="login-title">Sistem Informasi Anggaran</h1>
            <p class="login-subtitle">Akses Terbatas</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="#" class="login-form" id="loginForm">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label class="input-label">
                    <i class="fas fa-user"></i>
                    <span>Username</span>
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="username" class="form-input" placeholder="Masukkan username" required
                        autocomplete="username" autofocus id="usernameInput">
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="input-label">
                    <i class="fas fa-lock"></i>
                    <span>Password</span>
                </label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan password" required
                        autocomplete="current-password" id="passwordInput">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength" id="passwordStrength">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" class="remember-checkbox" id="remember">
                    <span>Ingat Saya</span>
                </label>
                <a href="#" class="forgot-password" id="forgotPassword">
                    Lupa Password?
                </a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn" id="submitBtn">
                <span id="btnText">Masuk ke Sistem</span>
                <i class="fas fa-sign-in-alt"></i>
            </button>

            <!-- Loading Dots -->
            <div class="loading-dots" id="loadingDots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </form>

        <!-- Alert Messages -->
        <div class="alert-container" id="alertContainer">
            <!-- Alerts will appear here -->
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; <span id="currentYear"></span> Sistem Informasi Anggaran v2.0</p>
            <div class="footer-links">
            </div>
        </div>
    </div>

</body>

</html>
