<?php
// process_order.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $game_name = filter_input(INPUT_POST, 'game_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $jockey_package = filter_input(INPUT_POST, 'jockey_package', FILTER_SANITIZE_SPECIAL_CHARS);
    $target_rank = filter_input(INPUT_POST, 'target_rank', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $signature = $_POST['signature_data'] ?? '';

    // Validasi basic
    if (empty($customer_name) || empty($game_name) || empty($jockey_package) || empty($target_rank) || empty($price) || empty($signature)) {
        header("Location: index.php?status=error&message=Data form tidak lengkap");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Simpan order ke tabel orders
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, game_name, jockey_package, target_rank, price, signature) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$customer_name, $game_name, $jockey_package, $target_rank, $price, $signature]);
        $order_id = $pdo->lastInsertId();

        // 2. Proses upload multiple files
        if (isset($_FILES['screenshots']) && !empty($_FILES['screenshots']['name'][0])) {
            $files = $_FILES['screenshots'];
            $upload_dir = 'uploads/';
            
            // Buat folder jika belum ada
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

            for ($i = 0; $i < count($files['name']); $i++) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_type = $files['type'][$i];
                $file_error = $files['error'][$i];

                if ($file_error === UPLOAD_ERR_OK) {
                    if (in_array($file_type, $allowed_types)) {
                        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                        $new_file_name = uniqid('ss_', true) . '.' . $ext;
                        $dest_path = $upload_dir . $new_file_name;

                        if (move_uploaded_file($file_tmp, $dest_path)) {
                            // Simpan path ke database order_screenshots
                            $stmt_ss = $pdo->prepare("INSERT INTO order_screenshots (order_id, file_path) VALUES (?, ?)");
                            $stmt_ss->execute([$order_id, $dest_path]);
                        }
                    } else {
                        // Tipe file tidak didukung, lewati atau batalkan
                        throw new Exception("Format gambar '" . htmlspecialchars($file_name) . "' tidak diizinkan. Hanya JPG, PNG, WEBP.");
                    }
                }
            }
        }

        $pdo->commit();
        header("Location: index.php?status=success");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: index.php?status=error&message=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
