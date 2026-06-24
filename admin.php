<?php
// admin.php
require_once 'config.php';

// Proteksi halaman admin: Cek apakah user sudah login dan role-nya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

try {
    // 1. Ambil data pesanan
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll();

    // 2. Ambil data statistik ringkas
    $total_orders = count($orders);
    
    $stmt_pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
    $pending_orders = $stmt_pending->fetchColumn();

    $stmt_completed = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'");
    $completed_orders = $stmt_completed->fetchColumn();

    $stmt_revenue = $pdo->query("SELECT SUM(price) FROM orders WHERE status = 'completed'");
    $total_revenue = $stmt_revenue->fetchColumn() ?: 0;

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - yassjokiin</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header & Navigation -->
    <header>
        <div class="nav-container">
            <a href="index.php" class="logo"><img src="assets/media/logo.jpg" alt="Logo" class="logo-img"><span class="logo-text">yassjokiin</span><span style="font-size:0.9rem; font-weight:400; color:var(--text-muted); margin-left: 0.2rem;">[Admin]</span></a>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="admin.php" class="btn btn-primary" style="color:#fff;"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="logout.php" style="color: var(--danger-color);"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
            <div class="control-buttons">
                <button class="btn-icon" id="muteToggle" title="Mute/Unmute"><i class="fas fa-volume-up"></i></button>
                <button class="btn-icon" id="themeToggle" title="Switch Mode"><i class="fas fa-moon"></i></button>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Welcoming User -->
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 800;">Selamat Datang, <span style="color: var(--primary-color);"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</h1>
                <p style="color: var(--text-muted);">Kelola data pesanan jockey game dengan mudah dan efisien.</p>
            </div>
            <div>
                <span class="badge badge-completed"><i class="fas fa-user-shield"></i> Level: Administrator</span>
            </div>
        </div>

        <!-- Alert messages -->
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'updated'): ?>
                <div style="background: rgba(0, 210, 211, 0.15); border: 1px solid var(--info-color); color: var(--info-color); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                    <i class="fas fa-edit"></i> Pesanan berhasil diperbarui!
                </div>
            <?php elseif ($_GET['status'] === 'deleted'): ?>
                <div style="background: rgba(255, 56, 56, 0.15); border: 1px solid var(--danger-color); color: var(--danger-color); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                    <i class="fas fa-trash-alt"></i> Pesanan berhasil dihapus secara permanen beserta file fisiknya!
                </div>
            <?php elseif ($_GET['status'] === 'error'): ?>
                <div style="background: rgba(255, 56, 56, 0.15); border: 1px solid var(--danger-color); color: var(--danger-color); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                    <i class="fas fa-exclamation-triangle"></i> Gagal memproses: <?php echo htmlspecialchars($_GET['message'] ?? 'Telah terjadi galat.'); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Stats Row -->
        <div class="grid-games" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 3rem;">
            <!-- Stat 1 -->
            <div class="card" style="display:flex; align-items:center; gap: 1.5rem; padding: 1.5rem;">
                <div style="background: var(--accent-glow); color: var(--primary-color); width: 60px; height: 60px; border-radius: 12px; display:flex; justify-content:center; align-items:center; font-size:1.8rem;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.8rem; font-weight:800;"><?php echo $total_orders; ?></h3>
                    <p style="color:var(--text-muted); font-size:0.9rem;">Total Pesanan</p>
                </div>
            </div>
            <!-- Stat 2 -->
            <div class="card" style="display:flex; align-items:center; gap: 1.5rem; padding: 1.5rem;">
                <div style="background: rgba(255, 179, 0, 0.1); color: var(--warning-color); width: 60px; height: 60px; border-radius: 12px; display:flex; justify-content:center; align-items:center; font-size:1.8rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.8rem; font-weight:800;"><?php echo $pending_orders; ?></h3>
                    <p style="color:var(--text-muted); font-size:0.9rem;">Menunggu Antrean</p>
                </div>
            </div>
            <!-- Stat 3 -->
            <div class="card" style="display:flex; align-items:center; gap: 1.5rem; padding: 1.5rem;">
                <div style="background: rgba(0, 255, 135, 0.1); color: var(--success-color); width: 60px; height: 60px; border-radius: 12px; display:flex; justify-content:center; align-items:center; font-size:1.8rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.8rem; font-weight:800;"><?php echo $completed_orders; ?></h3>
                    <p style="color:var(--text-muted); font-size:0.9rem;">Joki Selesai</p>
                </div>
            </div>
            <!-- Stat 4 -->
            <div class="card" style="display:flex; align-items:center; gap: 1.5rem; padding: 1.5rem;">
                <div style="background: rgba(127, 0, 255, 0.1); color: var(--secondary-color); width: 60px; height: 60px; border-radius: 12px; display:flex; justify-content:center; align-items:center; font-size:1.8rem;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.5rem; font-weight:800;" class="cell-price" data-price="<?php echo $total_revenue; ?>">Rp 0</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem;">Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Datatable Section -->
        <div class="card" style="padding: 1.5rem;">
            <div class="admin-controls">
                <h2 style="font-size: 1.5rem; font-weight:800;"><i class="fas fa-list"></i> Database Order Joki</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="datatableSearch" class="form-control" placeholder="Cari data joki secara instan...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table" id="datatableOrders">
                    <thead>
                        <tr>
                            <th>ID <i class="fas fa-sort"></i></th>
                            <th>Pelanggan <i class="fas fa-sort"></i></th>
                            <th>Game <i class="fas fa-sort"></i></th>
                            <th>Paket Joki <i class="fas fa-sort"></i></th>
                            <th>Target <i class="fas fa-sort"></i></th>
                            <th>Biaya <i class="fas fa-sort"></i></th>
                            <th>Status <i class="fas fa-sort"></i></th>
                            <th>Tanggal <i class="fas fa-sort"></i></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="9" style="text-align: center; color: var(--text-muted);">Belum ada data pesanan joki masuk.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><b><?php echo htmlspecialchars($order['customer_name']); ?></b></td>
                                    <td><?php echo htmlspecialchars($order['game_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['jockey_package']); ?></td>
                                    <td><span style="font-style: italic;"><?php echo htmlspecialchars($order['target_rank']); ?></span></td>
                                    <td class="cell-price" data-price="<?php echo $order['price']; ?>">Rp <?php echo number_format($order['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $order['status']; ?>">
                                            <?php 
                                            // Data conversion logic
                                            if ($order['status'] === 'pending') echo 'Menunggu';
                                            elseif ($order['status'] === 'processing') echo 'Proses';
                                            elseif ($order['status'] === 'completed') echo 'Selesai';
                                            elseif ($order['status'] === 'cancelled') echo 'Batal';
                                            else echo $order['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td class="cell-date" data-date="<?php echo $order['created_at']; ?>"><?php echo $order['created_at']; ?></td>
                                    <td>
                                        <div style="display:flex; gap:0.4rem;">
                                            <button onclick="viewOrderDetails(<?php echo $order['id']; ?>)" class="btn btn-secondary" style="padding:0.4rem 0.6rem; font-size:0.85rem;" title="Detail Pesanan"><i class="fas fa-eye"></i></button>
                                            <button onclick="editOrderDetails(<?php echo $order['id']; ?>)" class="btn btn-primary" style="padding:0.4rem 0.6rem; font-size:0.85rem;" title="Ubah Pesanan"><i class="fas fa-edit"></i></button>
                                            <button onclick="confirmDeleteOrder(<?php echo $order['id']; ?>)" class="btn btn-danger" style="padding:0.4rem 0.6rem; font-size:0.85rem;" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal View Detail -->
    <div class="modal" id="viewDetailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-info-circle"></i> Detail Pesanan Joki</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">ID ORDER</p>
                        <p id="view_id" style="font-weight:700;"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">TANGGAL MASUK</p>
                        <p id="view_date" style="font-weight:700;"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">NAMA PELANGGAN</p>
                        <p id="view_name" style="font-weight:700;"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">GAME</p>
                        <p id="view_game" style="font-weight:700;"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">PAKET JOKI</p>
                        <p id="view_package" style="font-weight:700;"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">TARGET RANK</p>
                        <p id="view_target" style="font-style: italic; font-weight:700;"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">ESTIMASI BIAYA</p>
                        <p id="view_price" style="font-weight:700; color:var(--primary-color);"></p>
                    </div>
                    <div>
                        <p style="color:var(--text-muted); font-size:0.85rem;">STATUS PENGERJAAN</p>
                        <span id="view_status" class="badge"></span>
                    </div>
                </div>

                <hr style="border:0; border-top:1px solid var(--card-border); margin: 1.5rem 0;">

                <div class="form-group">
                    <label><i class="fas fa-images"></i> Screenshot Akun (Multiple Files)</label>
                    <div id="view_screenshots" class="file-gallery">
                        <!-- Screenshots will load here -->
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label><i class="fas fa-signature"></i> Tanda Tangan Digital Pelanggan</label>
                    <div>
                        <img id="view_signature" class="signature-img" src="" alt="TTD Digital">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal('viewDetailModal')" class="btn btn-secondary">Tutup Detail</button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pesanan -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fas fa-edit"></i> Edit Data Pesanan</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form action="edit_order.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="form-group">
                        <label for="edit_customer_name">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="edit_customer_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_game_name">Pilih Game</label>
                        <input type="text" name="game_name" id="edit_game_name" class="form-control" readonly style="background: rgba(255, 255, 255, 0.05);">
                    </div>

                    <div class="form-group">
                        <label for="edit_jockey_package">Paket Joki</label>
                        <input type="text" name="jockey_package" id="edit_jockey_package" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_target_rank">Target Rank</label>
                        <input type="text" name="target_rank" id="edit_target_rank" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_price">Harga (IDR)</label>
                        <input type="number" name="price" id="edit_price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_status">Status Joki</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="pending">Pending (Menunggu)</option>
                            <option value="processing">Processing (Diproses)</option>
                            <option value="completed">Completed (Selesai)</option>
                            <option value="cancelled">Cancelled (Batal)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Confirmation -->
    <div class="modal" id="deleteModal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3 class="modal-title" style="color:var(--danger-color);"><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</h3>
                <button class="modal-close">&times;</button>
            </div>
            <form action="delete_order.php" method="POST">
                <div class="modal-body" style="text-align:center;">
                    <p style="margin-bottom:1rem; font-size:1.05rem;">Apakah Anda yakin ingin menghapus data pesanan ini?</p>
                    <p style="color:var(--text-muted); font-size:0.9rem;">Aksi ini akan menghapus semua screenshots dari disk server dan data database secara permanen.</p>
                    <input type="hidden" name="id" id="delete_id">
                </div>
                <div class="modal-footer" style="justify-content:center;">
                    <button type="button" onclick="closeModal('deleteModal')" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: var(--card-bg); border-top: 1px solid var(--card-border); padding: 2rem; text-align: center; margin-top: 4rem;">
        <p style="color: var(--text-muted);">&copy; 2026 yassjokiin. Hak Cipta Dilindungi Undang-Undang. Panel Dashboard Admin.</p>
    </footer>

    <!-- JS Scripts -->
    <script src="assets/js/script.js"></script>
    <script>
        // Fetch order details via AJAX and display in Detail Modal
        function viewOrderDetails(id) {
            fetch(`edit_order.php?id=${id}`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(order => {
                    document.getElementById('view_id').innerText = '#' + order.id;
                    
                    // Format Date
                    const dateObj = new Date(order.created_at);
                    document.getElementById('view_date').innerText = dateObj.toLocaleDateString('id-ID', {
                        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                    }) + ' WIB';

                    document.getElementById('view_name').innerText = order.customer_name;
                    document.getElementById('view_game').innerText = order.game_name;
                    document.getElementById('view_package').innerText = order.jockey_package;
                    document.getElementById('view_target').innerText = order.target_rank;

                    // Format Price
                    document.getElementById('view_price').innerText = new Intl.NumberFormat('id-ID', {
                        style: 'currency', currency: 'IDR', maximumFractionDigits: 0
                    }).format(order.price);

                    // Status Badge conversion
                    const statusBadge = document.getElementById('view_status');
                    statusBadge.className = 'badge badge-' + order.status;
                    let statText = order.status;
                    if (order.status === 'pending') statText = 'Menunggu';
                    else if (order.status === 'processing') statText = 'Proses';
                    else if (order.status === 'completed') statText = 'Selesai';
                    else if (order.status === 'cancelled') statText = 'Batal';
                    statusBadge.innerText = statText;

                    // Load Digital Signature
                    document.getElementById('view_signature').src = order.signature;

                    // Load Screenshots list
                    const ssContainer = document.getElementById('view_screenshots');
                    ssContainer.innerHTML = '';
                    if (order.screenshots && order.screenshots.length > 0) {
                        order.screenshots.forEach(ss => {
                            const link = document.createElement('a');
                            link.href = ss.file_path;
                            link.target = '_blank';
                            link.className = 'file-preview';
                            
                            const img = document.createElement('img');
                            img.src = ss.file_path;
                            img.alt = 'Screenshot';
                            
                            link.appendChild(img);
                            ssContainer.appendChild(link);
                        });
                    } else {
                        ssContainer.innerText = 'Tidak ada screenshot diunggah.';
                    }

                    openModal('viewDetailModal');
                })
                .catch(error => {
                    alert('Gagal mengambil data detail: ' + error.message);
                });
        }

        // Fetch order details via AJAX and fill in Edit Form Modal
        function editOrderDetails(id) {
            fetch(`edit_order.php?id=${id}`)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal mengambil data");
                    return response.json();
                })
                .then(order => {
                    document.getElementById('edit_id').value = order.id;
                    document.getElementById('edit_customer_name').value = order.customer_name;
                    document.getElementById('edit_game_name').value = order.game_name;
                    document.getElementById('edit_jockey_package').value = order.jockey_package;
                    document.getElementById('edit_target_rank').value = order.target_rank;
                    document.getElementById('edit_price').value = Math.round(order.price);
                    document.getElementById('edit_status').value = order.status;

                    openModal('editModal');
                })
                .catch(error => {
                    alert('Gagal mengambil data edit: ' + error.message);
                });
        }

        // Pass ID to Delete Modal Form
        function confirmDeleteOrder(id) {
            document.getElementById('delete_id').value = id;
            openModal('deleteModal');
        }
    </script>
</body>
</html>
