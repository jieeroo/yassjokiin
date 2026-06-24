<?php
// login.php
require_once 'config.php';

// Jika sudah login, langsung ke admin
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: admin.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: admin.php');
                exit;
            } else {
                $error_message = 'Username atau Password salah!';
            }
        } catch (PDOException $e) {
            $error_message = 'Terjadi kesalahan sistem: ' . $e->getMessage();
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
    <title>Login Admin - yassjokiin</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="index.php" class="logo"><img src="assets/media/logo.jpg" alt="Logo" class="logo-img"><span class="logo-text">yassjokiin</span></a>
            <div class="control-buttons">
                <button class="btn-icon" id="muteToggle" title="Mute/Unmute"><i class="fas fa-volume-up"></i></button>
                <button class="btn-icon" id="themeToggle" title="Switch Mode"><i class="fas fa-moon"></i></button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Home</a>
            </div>
        </div>
    </header>

    <main class="container" style="display: flex; justify-content: center; align-items: center; min-height: 75vh;">
        <div class="card" style="width: 100%; max-width: 420px; animation: fadeInUp 0.8s ease;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 2.2rem; font-weight: 800; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Sign In Admin</h2>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Akses dashboard joki game yassjokiin</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div style="background: rgba(255, 56, 56, 0.15); border: 1px solid var(--danger-color); color: var(--danger-color); padding: 0.8rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center; font-size: 0.95rem;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus autocomplete="off">
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;"><i class="fas fa-sign-in-alt"></i> Masuk Sekarang</button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">
                <span>Default Admin Login: <b>admin</b> / <b>admin123</b></span>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
