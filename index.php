<?php
session_start();

// Jika sudah login, langsung redirect sesuai role
if (isset($_SESSION['username'])) {
    $routes = [1 => 'dashboard/index.php', 2 => 'dashboard/info.php'];
    $dest   = $routes[$_SESSION['status']] ?? 'dashboard/history.php';
    header('Location: ' . $dest);
    exit;
}

// Ambil pesan error dari session
$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/ubl.png">
    <title>Login — The Bellagio Mansion Security</title>
    
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        /* Reset & Base Styles */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9; /* Slate 100 */
            padding: 1.5rem;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.6s ease-out;
        }

        /* 1. Brand Header & Logo Placement */
        .brand-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-wrapper {
            width: 250px;
            height: 100px;
            background: #ffffff;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 18px;
        }

        .logo-wrapper img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }

        .brand-section h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a; /* Slate 900 */
            letter-spacing: -0.025em;
        }

        .brand-section p {
            font-size: 14px;
            color: #64748b; /* Slate 500 */
            margin-top: 4px;
        }

        /* 2. Login Card */
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .form-group { margin-bottom: 1.5rem; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-group { position: relative; }

        .input-group i.input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            transition: color 0.2s;
        }

        .input-group input {
            width: 100%;
            height: 50px;
            padding: 0 16px 0 48px;
            font-size: 15px;
            color: #1e293b;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-group input:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-group input:focus + i.input-icon {
            color: #2563eb;
        }

        /* Password Toggle */
        .pw-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 4px;
            transition: color 0.2s;
        }

        .pw-toggle:hover { color: #1e293b; }

        /* 3. Error Feedback */
        .error-alert {
            background: #fff1f2;
            border-left: 4px solid #e11d48;
            padding: 12px 16px;
            font-size: 14px;
            color: #9f1239;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.4s ease-in-out;
        }

        /* 4. Action Button */
        .btn-submit {
            width: 100%;
            height: 50px;
            background: #1e3a8a; /* Deep Blue */
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: #1e40af;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
        }

        .btn-submit:active { transform: translateY(0); }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2.5rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>

<div class="login-container">
    <header class="brand-section">
        <div class="logo-wrapper">
            <img src="assets/images/bellman.png" alt="The Bellagio Mansion Logo">
        </div>
        <h1>The Bellagio Mansion</h1>
        <p>Sistem Keamanan Dokumen & Kriptografi</p>
    </header>

    <main class="login-card">
        <?php if (!empty($loginError)): ?>
        <div class="error-alert">
            <i class="fa fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($loginError) ?></span>
        </div>
        <?php endif; ?>

        <form action="auth.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-group">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" id="username" name="username" 
                           placeholder="Masukkan ID Pengguna" 
                           autocomplete="off" autofocus required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" 
                           placeholder="Masukkan Kata Sandi" required>
                    <button type="button" class="pw-toggle" id="btn-toggle">
                        <i class="fa fa-eye" id="icon-toggle"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn-submit">
                Login <i class="fa fa-arrow-right"></i>
            </button>
        </form>
    </main>

    <footer class="footer-text">
        &copy; <?= date('Y') ?> The Bellagio Mansion. All Rights Reserved.
    </footer>
</div>

<script>
    // Handle Show/Hide Password
    const btnToggle  = document.getElementById('btn-toggle');
    const inputPass  = document.getElementById('password');
    const iconToggle = document.getElementById('icon-toggle');

    btnToggle.addEventListener('click', () => {
        const type = inputPass.getAttribute('type') === 'password' ? 'text' : 'password';
        inputPass.setAttribute('type', type);
        
        // Toggle Icon Class
        iconToggle.classList.toggle('fa-eye');
        iconToggle.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>