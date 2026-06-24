<?php
// index.php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>yassjokiin - Jasa Jockey Game Murah & Terpercaya</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header & Navigation -->
    <header>
        <div class="nav-container">
            <a href="index.php" class="logo"><img src="assets/img/♡ ₊ profile ⁺ shorekeeper - wuwa.jpg" alt="Logo" class="logo-img"><span class="logo-text">yassjokiin</span></a>
            <ul class="nav-links">
                <li><a href="#games">List Game</a></li>
                <li><a href="#order">Form Order</a></li>
                <li><a href="#promo">Promo Video</a></li>
                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin.php" class="btn btn-primary" style="color: #fff; padding: 0.5rem 1rem;"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <?php else: ?>
                        <li><span style="font-weight: 600; color: var(--primary-color);"><i class="fas fa-user-circle"></i> Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                        <li><a href="#history"><i class="fas fa-history"></i> Riwayat</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" style="color: var(--danger-color);"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn btn-secondary" style="padding: 0.5rem 1rem;"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="register.php" class="btn btn-primary" style="color: #fff; padding: 0.5rem 1rem;"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>
            <div class="control-buttons">
                <button class="btn-icon" id="muteToggle" title="Mute/Unmute"><i class="fas fa-volume-up"></i></button>
                <button class="btn-icon" id="themeToggle" title="Switch Mode"><i class="fas fa-moon"></i></button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Optional fallback CSS animation background instead of heavy video -->
        <div class="hero-content">
            <h1>Joki Game Naik Rank Instan di <span>yassjokiin</span></h1>
            <p>Jasa jockey game online terpercaya, amanah, dan paling cepat pengerjaannya. Didukung pro-player berpengalaman untuk Mobile Legends, Valorant, PUBG, dan Genshin Impact.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="#order" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Order Sekarang</a>
                <button onclick="openModal('guidelinesModal')" class="btn btn-secondary"><i class="fas fa-book-open"></i> Cara Pemesanan</button>
            </div>
        </div>
    </section>

    <!-- Games Section -->
    <section id="games" class="container">
        <h2 class="section-title">List Game Joki Terpopuler</h2>
        <div class="grid-games">
            <!-- Game Card 1 -->
            <div class="card game-card" onclick="selectGameOption('Mobile Legends')">
                <img src="assets/img/ML.jpg" alt="Mobile Legends" class="game-img">
                <div class="game-info">
                    <h3>Mobile Legends</h3>
                    <p>Push rank Mythic Glory dengan winrate tinggi. Aman & pengerjaan kilat.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--primary-color); font-weight: 700;">Mulai Rp 50.000</span>
                        <span class="badge badge-completed">Tersedia</span>
                    </div>
                </div>
            </div>
            <!-- Game Card 2 -->
            <div class="card game-card" onclick="selectGameOption('Valorant')">
                <img src="assets/img/Valorant Icon.jpg" alt="Valorant" class="game-img">
                <div class="game-info">
                    <h3>Valorant</h3>
                    <p>Push rank Radiant dengan party pro-player global. Server ID/SG.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--primary-color); font-weight: 700;">Mulai Rp 120.000</span>
                        <span class="badge badge-completed">Tersedia</span>
                    </div>
                </div>
            </div>
            <!-- Game Card 3 -->
            <div class="card game-card" onclick="selectGameOption('PUBG Mobile')">
                <img src="assets/img/PUBGM.jpg" alt="PUBG Mobile" class="game-img">
                <div class="game-info">
                    <h3>PUBG Mobile</h3>
                    <p>Push rank Conqueror aman, anti-banned, 100% garansi kembali.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--primary-color); font-weight: 700;">Mulai Rp 80.000</span>
                        <span class="badge badge-completed">Tersedia</span>
                    </div>
                </div>
            </div>
            <!-- Game Card 4 -->
            <div class="card game-card" onclick="selectGameOption('Genshin Impact')">
                <img src="assets/img/GIjpg.jpg" alt="Genshin Impact" class="game-img">
                <div class="game-info">
                    <h3>Genshin Impact</h3>
                    <p>Jasa spiral abyss, farm material, quest & eksplorasi map 100%.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--primary-color); font-weight: 700;">Mulai Rp 70.000</span>
                        <span class="badge badge-completed">Tersedia</span>
                    </div>
                </div>
            </div>
            <!-- Game Card 5 -->
            <div class="card game-card" onclick="selectGameOption('Wuthering Waves')">
                <img src="assets/img/wuwa.jpg" alt="Wuthering Waves" class="game-img">
                <div class="game-info">
                    <h3>Wuthering Waves</h3>
                    <p>Jasa push level, clearing quest, farming echo, data dock booster.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--primary-color); font-weight: 700;">Mulai Rp 60.000</span>
                        <span class="badge badge-completed">Tersedia</span>
                    </div>
                </div>
            </div>
            <!-- Game Card 6 -->
            <div class="card game-card" onclick="selectGameOption('Bloodstrike')">
                <img src="assets/img/bs.jpg" alt="Bloodstrike" class="game-img">
                <div class="game-info">
                    <h3>Bloodstrike</h3>
                    <p>Push rank Legend cepat, kill rate tinggi, leveling weapon aman.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--primary-color); font-weight: 700;">Mulai Rp 70.000</span>
                        <span class="badge badge-completed">Tersedia</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Promo Section -->
    <section id="promo" class="container video-section">
        <h2 class="section-title">Video Cuplikan Joki & Gameplay</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto 2rem;">Tonton video testimoni pengerjaan joki game dari para booster pro player kami saat mendominasi server!</p>
        <div class="video-container">
            <video controls poster="assets/img/Shorekeeper GIF.gif">
                <source src="assets/img/video gameplay.mp4" type="video/mp4">
                Browser Anda tidak mendukung tag video HTML5.
            </video>
        </div>
    </section>

    <!-- Order Form Section -->
    <section id="order" class="container" style="max-width: 800px;">
        <div class="card">
            <h2 class="section-title" style="margin-bottom: 2rem;">Formulir Pemesanan Joki</h2>
            
            <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
                <div style="background: rgba(0, 255, 135, 0.15); border: 1px solid var(--success-color); color: var(--success-color); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                    <i class="fas fa-user-check"></i> Registrasi berhasil! Akun Anda telah terdaftar dan otomatis masuk. Silakan lakukan pemesanan di bawah.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div style="background: rgba(0, 255, 135, 0.15); border: 1px solid var(--success-color); color: var(--success-color); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                    <i class="fas fa-check-circle"></i> Pesanan berhasil dikirim! Silakan pantau perkembangan joki Anda pada tabel riwayat di bawah.
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div style="background: rgba(255, 56, 56, 0.15); border: 1px solid var(--danger-color); color: var(--danger-color); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                    <i class="fas fa-times-circle"></i> Terjadi kesalahan: <?php echo htmlspecialchars($_GET['message'] ?? 'Gagal memproses order'); ?>
                </div>
            <?php endif; ?>

            <form id="orderForm" action="process_order.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="customer_name"><i class="fas fa-user"></i> Nama Pelanggan</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Masukkan nama lengkap Anda" required 
                           value="<?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'user') ? htmlspecialchars($_SESSION['username']) : ''; ?>"
                           <?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'user') ? 'readonly style="background: rgba(255, 255, 255, 0.05);"' : ''; ?>>
                </div>

                <div class="grid-games" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0;">
                    <div class="form-group">
                        <label for="game_name"><i class="fas fa-gamepad"></i> Pilih Game</label>
                        <select name="game_name" id="game_name" class="form-control" required>
                            <option value="">-- Pilih Game --</option>
                            <option value="Mobile Legends">Mobile Legends</option>
                            <option value="Valorant">Valorant</option>
                            <option value="PUBG Mobile">PUBG Mobile</option>
                            <option value="Genshin Impact">Genshin Impact</option>
                            <option value="Wuthering Waves">Wuthering Waves</option>
                            <option value="Bloodstrike">Bloodstrike</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jockey_package"><i class="fas fa-box"></i> Paket Joki</label>
                        <select name="jockey_package" id="jockey_package" class="form-control" required>
                            <option value="">-- Pilih Paket --</option>
                        </select>
                    </div>
                </div>

                <div class="grid-games" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0;">
                    <div class="form-group">
                        <label for="target_rank"><i class="fas fa-crosshairs"></i> Target Rank / Level</label>
                        <input type="text" name="target_rank" id="target_rank" class="form-control" placeholder="Contoh: Mythic III atau Level 60" required>
                    </div>

                    <div class="form-group">
                        <label for="price_display"><i class="fas fa-tag"></i> Estimasi Harga (IDR)</label>
                        <!-- Price display formatted using JS. Actual sent price is in hidden input -->
                        <input type="text" id="price_display" class="form-control" readonly style="background: rgba(255, 255, 255, 0.05); font-weight: 700; color: var(--primary-color);">
                        <input type="hidden" name="price" id="price_value" required>
                    </div>
                </div>

                <!-- Multiple Files Upload -->
                <div class="form-group">
                    <label for="screenshots"><i class="fas fa-images"></i> Unggah Screenshot Akun (Bisa pilih banyak file)</label>
                    <input type="file" name="screenshots[]" id="screenshots" class="form-control" multiple accept="image/*" required>
                    <small style="color: var(--text-muted); display: block; margin-top: 0.3rem;">Unggah bukti profil, rank saat ini, atau item inventori dalam format JPG/PNG/WEBP.</small>
                    <div id="filePreviewContainer" class="file-gallery"></div>
                </div>

                <!-- Digital Signature Canvas -->
                <div class="form-group">
                    <label><i class="fas fa-signature"></i> Tanda Tangan Digital Pelanggan (Wajib)</label>
                    <div class="canvas-container">
                        <canvas id="signatureCanvas"></canvas>
                        <div class="canvas-controls">
                            <button type="button" id="clearSignature" class="btn btn-secondary" style="padding: 0.4rem 1rem; font-size: 0.85rem;"><i class="fas fa-eraser"></i> Hapus</button>
                        </div>
                    </div>
                    <small style="color: var(--text-muted);">Gunakan mouse Anda (pada PC) atau jari (pada smartphone) untuk menandatangani.</small>
                    <!-- Hidden input to store base64 signature -->
                    <input type="hidden" name="signature_data" id="signatureData" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 1rem;"><i class="fas fa-paper-plane"></i> Kirim Formulir Joki</button>
            </form>
        </div>
    </section>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
        <!-- Order History Section for Logged-in Customer -->
        <section id="history" class="container" style="max-width: 800px; padding-top: 0;">
            <div class="card">
                <h2 class="section-title" style="margin-bottom: 2rem;"><i class="fas fa-history"></i> Riwayat Pemesanan Anda</h2>
                <?php
                try {
                    $stmt_history = $pdo->prepare("SELECT * FROM orders WHERE customer_name = ? ORDER BY created_at DESC");
                    $stmt_history->execute([$_SESSION['username']]);
                    $user_orders = $stmt_history->fetchAll();
                } catch (PDOException $e) {
                    $user_orders = [];
                }
                ?>
                
                <?php if (empty($user_orders)): ?>
                    <p style="text-align: center; color: var(--text-muted); padding: 1rem 0;">Anda belum melakukan pemesanan joki game apa pun.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="custom-table" style="font-size:0.95rem;">
                            <thead>
                                <tr>
                                    <th>Game</th>
                                    <th>Paket</th>
                                    <th>Target</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user_orders as $u_order): ?>
                                    <tr>
                                        <td><b><?php echo htmlspecialchars($u_order['game_name']); ?></b></td>
                                        <td><?php echo htmlspecialchars($u_order['jockey_package']); ?></td>
                                        <td><span style="font-style: italic;"><?php echo htmlspecialchars($u_order['target_rank']); ?></span></td>
                                        <td class="cell-price" data-price="<?php echo $u_order['price']; ?>">Rp <?php echo number_format($u_order['price'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $u_order['status']; ?>">
                                                <?php
                                                if ($u_order['status'] === 'pending') echo 'Menunggu';
                                                elseif ($u_order['status'] === 'processing') echo 'Proses';
                                                elseif ($u_order['status'] === 'completed') echo 'Selesai';
                                                elseif ($u_order['status'] === 'cancelled') echo 'Batal';
                                                else echo $u_order['status'];
                                                ?>
                                            </span>
                                        </td>
                                        <td class="cell-date" data-date="<?php echo $u_order['created_at']; ?>"><?php echo $u_order['created_at']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Modal Cara Pemesanan -->
    <div class="modal" id="guidelinesModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-book-open"></i> Cara Pemesanan Joki</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 1rem; line-height: 1.6;">Selamat datang di <b>yassjokiin</b>! Ikuti petunjuk sederhana ini untuk melakukan pemesanan joki game:</p>
                <ol style="margin-left: 1.5rem; line-height: 1.8;">
                    <li>Pilih game terfavorit Anda pada list joki game yang tersedia.</li>
                    <li>Isi data lengkap Anda pada formulir pemesanan.</li>
                    <li>Unggah tangkapan layar (screenshot) karakter/profil game Anda guna pencocokan data akun.</li>
                    <li>Bubuhkan <b>Tanda Tangan Digital</b> Anda secara langsung di canvas yang disediakan sebagai tanda persetujuan syarat ketentuan joki.</li>
                    <li>Klik <b>Kirim Formulir Joki</b>. Tim admin kami akan segera menghubungi Anda melalui detail kontak yang didapatkan untuk eksekusi jockeying.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal('guidelinesModal')" class="btn btn-primary">Saya Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: var(--card-bg); border-top: 1px solid var(--card-border); padding: 2rem; text-align: center; margin-top: 4rem;">
        <p style="color: var(--text-muted);">&copy; 2026 yassjokiin. Hak Cipta Dilindungi Undang-Undang. Tugas Akhir Kuliah Pemrograman Web.</p>
    </footer>

    <!-- JS Scripts -->
    <script src="assets/js/script.js"></script>
    <script>
        // Game-based Jockey Packages pricing options
        const packageData = {
            'Mobile Legends': [
                { name: 'Rank Up: Epic -> Legend', price: 100000 },
                { name: 'Rank Up: Legend -> Mythic', price: 150000 },
                { name: 'Level Up Emblem: 10 Level', price: 50000 },
                { name: 'Jasa Winrate: 5 Pertandingan Klasik', price: 60000 }
            ],
            'Valorant': [
                { name: 'Rank Up: Silver -> Gold', price: 120000 },
                { name: 'Rank Up: Gold -> Platinum', price: 200000 },
                { name: 'Placement Match: 5 Game', price: 150000 },
                { name: 'Push Level: 10 Level Akun', price: 180000 }
            ],
            'PUBG Mobile': [
                { name: 'Rank Up: Gold -> Platinum', price: 80000 },
                { name: 'Rank Up: Platinum -> Diamond', price: 120000 },
                { name: 'Push Rank: Diamond -> Crown', price: 200000 },
                { name: 'KD Booster: 10 Match', price: 100000 }
            ],
            'Genshin Impact': [
                { name: 'Quest Clear: Archon Quest Liyue', price: 100000 },
                { name: 'Quest Clear: Archon Quest Inazuma', price: 180000 },
                { name: 'Farming Material: 100 Local Specialty', price: 80000 },
                { name: 'Daily Commission: 7 Hari', price: 70000 }
            ],
            'Wuthering Waves': [
                { name: 'Push Level: Union Level 1-30', price: 150000 },
                { name: 'Push Level: Union Level 30-50', price: 250000 },
                { name: 'Daily + Astrite Farming (7 Hari)', price: 90000 },
                { name: 'Echo Farming: 10 Boss Runs', price: 60000 }
            ],
            'Bloodstrike': [
                { name: 'Rank Up: Gold -> Platinum', price: 70000 },
                { name: 'Rank Up: Platinum -> Master', price: 150000 },
                { name: 'Push Rank: Master -> Legend', price: 250000 },
                { name: 'Leveling: Weapon Level Max', price: 80000 }
            ]
        };

        const gameSelect = document.getElementById('game_name');
        const packageSelect = document.getElementById('jockey_package');
        const priceDisplay = document.getElementById('price_display');
        const priceValue = document.getElementById('price_value');
        const screenshotsInput = document.getElementById('screenshots');
        const previewContainer = document.getElementById('filePreviewContainer');

        // Dynamic select boxes updating
        gameSelect.addEventListener('change', (e) => {
            const game = e.target.value;
            packageSelect.innerHTML = '<option value="">-- Pilih Paket --</option>';
            priceDisplay.value = '';
            priceValue.value = '';

            if (game && packageData[game]) {
                packageData[game].forEach(pkg => {
                    const opt = document.createElement('option');
                    opt.value = pkg.name;
                    opt.textContent = pkg.name;
                    opt.setAttribute('data-price', pkg.price);
                    packageSelect.appendChild(opt);
                });
            }
        });

        // Click on game cards auto-selects game in form
        function selectGameOption(gameName) {
            gameSelect.value = gameName;
            // Trigger change manually
            const event = new Event('change');
            gameSelect.dispatchEvent(event);
            // Smooth scroll to order section
            document.getElementById('order').scrollIntoView({ behavior: 'smooth' });
        }

        packageSelect.addEventListener('change', (e) => {
            const selectedOpt = packageSelect.options[packageSelect.selectedIndex];
            const price = selectedOpt.getAttribute('data-price');
            
            if (price) {
                priceValue.value = price;
                // Convert price display format
                priceDisplay.value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(price);
            } else {
                priceDisplay.value = '';
                priceValue.value = '';
            }
        });

        // Screenshot upload multiple previews
        screenshotsInput.addEventListener('change', () => {
            previewContainer.innerHTML = '';
            const files = Array.from(screenshotsInput.files);
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'file-preview';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    
                    previewDiv.appendChild(img);
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>
</html>
