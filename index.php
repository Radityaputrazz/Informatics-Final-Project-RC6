<?php
session_start();

// Jika sudah login, langsung redirect
if (isset($_SESSION['username'])) {
    $routes = [1 => 'dashboard/index.php', 2 => 'dashboard/info.php'];
    $dest   = $routes[$_SESSION['status']] ?? 'dashboard/history.php';
    header('Location: ' . $dest);
    exit;
}

// Ambil pesan error dari session (dikirim oleh auth.php)
$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login — The Bellagio Mansion</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f5f4f1;
      padding: 1rem;
    }

    .wrapper { width: 100%; max-width: 380px; }

    .header { text-align: center; margin-bottom: 2rem; }

    .logo-circle {
      width: 56px; height: 56px;
      border-radius: 50%;
      background: #ffffff;
      border: 1px solid #e0ddd6;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1rem;
    }

    .header h1 { font-size: 18px; font-weight: 500; color: #1a1a18; }
    .header p  { font-size: 13px; color: #888780; margin-top: 4px; }

    .card {
      background: #ffffff;
      border: 1px solid #e8e6e0;
      border-radius: 12px;
      padding: 1.75rem 1.5rem;
    }

    .field { margin-bottom: 1rem; }

    label {
      display: block;
      font-size: 11px; font-weight: 500;
      color: #888780;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 6px;
    }

    .input-wrap { position: relative; }

    .input-wrap svg.field-icon {
      position: absolute; left: 10px; top: 50%;
      transform: translateY(-50%);
      width: 15px; height: 15px;
      stroke: #888780;
      pointer-events: none;
    }

    .input-wrap input {
      width: 100%; height: 40px;
      padding: 0 12px 0 34px;
      font-size: 14px; font-family: inherit;
      color: #1a1a18;
      background: #f5f4f1;
      border: 1px solid #d3d1c7;
      border-radius: 8px;
      outline: none;
      transition: border-color 0.15s;
    }

    .input-wrap input:focus {
      border-color: #888780;
      background: #ffffff;
    }

    .input-wrap input::placeholder { color: #b4b2a9; }

    .toggle-btn {
      position: absolute; right: 10px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      cursor: pointer; padding: 0;
      display: flex; align-items: center;
      color: #888780;
    }

    .toggle-btn:hover { color: #1a1a18; }

    /* Error box */
    .error-box {
      background: #fcebeb;
      border: 1px solid #f09595;
      border-radius: 8px;
      padding: 10px 12px;
      font-size: 13px;
      color: #a32d2d;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-login {
      width: 100%; height: 42px;
      background: #1a1a18; color: #ffffff;
      border: none; border-radius: 8px;
      font-size: 14px; font-weight: 500;
      font-family: inherit; cursor: pointer;
      display: flex; align-items: center;
      justify-content: center; gap: 8px;
      margin-top: 1.5rem;
      transition: opacity 0.15s;
    }

    .btn-login:hover  { opacity: 0.85; }
    .btn-login:active { transform: scale(0.98); }

    .footer {
      text-align: center;
      font-size: 12px; color: #b4b2a9;
      margin-top: 1.25rem;
    }
  </style>
</head>
<body>

<div class="wrapper">

  <div class="header">
    <div class="logo-circle">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#888780" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
    <h1>The Bellagio Mansion</h1>
    <p>Masuk ke akun Anda</p>
  </div>

  <div class="card">

    <!-- Pesan error dari auth.php -->
    <?php if (!empty($loginError)): ?>
    <div class="error-box">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <?= htmlspecialchars($loginError) ?>
    </div>
    <?php endif; ?>

    <!-- Form — action ke auth.php, method POST -->
    <form action="auth.php" method="post">

      <div class="field">
        <label for="username">Username</label>
        <div class="input-wrap">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          <input type="text" id="username" name="username"
            placeholder="Masukkan username"
            autocomplete="off" autofocus required>
        </div>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="input-wrap">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0110 0v4"/>
          </svg>
          <input type="password" id="password" name="password"
            placeholder="Masukkan password" required>
          <button type="button" class="toggle-btn" id="toggle-pw" aria-label="Tampilkan password">
            <svg id="eye-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- name="login" wajib ada agar auth.php mengenali request -->
      <button type="submit" name="login" class="btn-login">
        Masuk
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/>
          <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
      </button>

    </form>
  </div>

  <p class="footer">The Bellagio Mansion &copy; <?= date('Y') ?></p>
</div>

<script>
  const toggleBtn = document.getElementById('toggle-pw');
  const pwInput   = document.getElementById('password');
  const eyeIcon   = document.getElementById('eye-icon');

  const eyeOpen = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  const eyeOff  = `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

  toggleBtn.addEventListener('click', () => {
    const isPassword  = pwInput.type === 'password';
    pwInput.type      = isPassword ? 'text' : 'password';
    eyeIcon.innerHTML = isPassword ? eyeOff : eyeOpen;
  });
</script>

</body>
</html>