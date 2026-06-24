<?php
// edit_order.php
require_once 'config.php';

// Proteksi halaman: pastikan hanya admin yang bisa edit
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $game_name = filter_input(INPUT_POST, 'game_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $jockey_package = filter_input(INPUT_POST, 'jockey_package', FILTER_SANITIZE_SPECIAL_CHARS);
    $target_rank = filter_input(INPUT_POST, 'target_rank', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$id || empty($customer_name) || empty($game_name) || empty($status)) {
        header("Location: admin.php?status=error&message=Data update tidak valid");
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE orders SET customer_name = ?, game_name = ?, jockey_package = ?, target_rank = ?, price = ?, status = ? WHERE id = ?");
        $stmt->execute([$customer_name, $game_name, $jockey_package, $target_rank, $price, $status, $id]);

        header("Location: admin.php?status=updated");
        exit;
    } catch (PDOException $e) {
        header("Location: admin.php?status=error&message=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Jika request GET untuk mengambil detail order dalam format JSON (misal untuk isi data modal)
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        try {
            // Get order data
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$id]);
            $order = $stmt->fetch();

            if ($order) {
                // Get screenshots
                $stmt_ss = $pdo->prepare("SELECT id, file_path FROM order_screenshots WHERE order_id = ?");
                $stmt_ss->execute([$id]);
                $screenshots = $stmt_ss->fetchAll();
                
                $order['screenshots'] = $screenshots;

                header('Content-Type: application/json');
                echo json_encode($order);
                exit;
            }
        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Invalid Request']);
    exit;
}
?>
