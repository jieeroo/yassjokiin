<?php
// register.php
require_once 'config.php';

// Jika sudah login, redirect
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if (strlen($username) < 4) {
            $error_message = 'Username minimal 4 karakter!';
        } elseif (strlen($password) < 6) {
            $error_message = 'Password minimal 6 karakter!';
        } elseif ($password !== $confirm_password) {
            $error_message = 'Konfirmasi password tidak cocok!';
        } else {
            try {
                // Cek apakah username sudah ada
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetchColumn() > 0) {
                    $error_message = 'Username sudah terdaftar! Pilih username lain.';
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Simpan user dengan role 'user'
                    $stmt_ins = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
                    $stmt_ins->execute([$username, $hashed_password]);

                    // Login otomatis
                    $user_id = $pdo->lastInsertId();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'user';

                    // Redirect ke landing page dengan status sukses
                    header('Location: index.php?register=success');
                    exit;
                }
            } catch (PDOException $e) {
                $error_message = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            }
        }
    } else {
        $error_message = 'Harap isi semua field!';
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - yassjokiin</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="index.php" class="logo"><img src="assets/img/♡ ₊ profile ⁺ shorekeeper - wuwa.jpg" alt="Logo" class="logo-img"><span class="logo-text">yassjokiin</span></a>
            <div class="control-buttons">
                <button class="btn-icon" id="muteToggle" title="Mute/Unmute"><i class="fas fa-volume-up"></i></button>
                <button class="btn-icon" id="themeToggle" title="Switch Mode"><i class="fas fa-moon"></i></button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Home</a>
            </div>
        </div>
    </header>

    <main class="container" style="display: flex; justify-content: center; align-items: center; min-height: 75vh;">
        <div class="card" style="width: 100%; max-width: 440px; animation: fadeInUp 0.8s ease;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 2.2rem; font-weight: 800; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Daftar Pelanggan</h2>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Buat akun untuk melacak riwayat joki Anda</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div style="background: rgba(255, 56, 56, 0.15); border: 1px solid var(--danger-color); color: var(--danger-color); padding: 0.8rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center; font-size: 0.95rem;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Minimal 4 karakter" required autocomplete="off" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="confirm_password"><i class="fas fa-key"></i> Konfirmasi Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password Anda" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;"><i class="fas fa-user-plus"></i> Daftar Sekarang</button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.95rem; color: var(--text-muted);">
                <span>Sudah punya akun? <a href="login.php" style="color: var(--primary-color); text-decoration: none; font-weight:600;">Login di sini</a></span>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
