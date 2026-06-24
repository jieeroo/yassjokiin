<?php
// delete_order.php
require_once 'config.php';

// Proteksi halaman: pastikan hanya admin yang bisa delete
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        header("Location: admin.php?status=error&message=ID tidak valid");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Ambil path semua file screenshot untuk dihapus secara fisik
        $stmt_ss = $pdo->prepare("SELECT file_path FROM order_screenshots WHERE order_id = ?");
        $stmt_ss->execute([$id]);
        $screenshots = $stmt_ss->fetchAll();

        foreach ($screenshots as $ss) {
            $file_path = $ss['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path); // Hapus file fisik dari disk
            }
        }

        // 2. Hapus data order (akan cascade delete data screenshot di db)
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        header("Location: admin.php?status=deleted");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: admin.php?status=error&message=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: admin.php");
    exit;
}
?>
