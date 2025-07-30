<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID perangkat tidak valid.";
    exit;
}

$id = intval($_GET['id']);

// Ambil IP perangkat sebelum dihapus
$stmt = $conn->prepare("SELECT ip_address FROM perangkat WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Data perangkat tidak ditemukan.";
    exit;
}

$row = $result->fetch_assoc();
$ip = $row['ip_address'];
$stmt->close();

// Hapus relasi terlebih dahulu
$conn->query("DELETE FROM relasi_perangkat WHERE dari_id = $id OR ke_id = $id");

// Lalu hapus perangkat
$conn->query("DELETE FROM perangkat WHERE id = $id");

// Hapus perangkat
$stmt = $conn->prepare("DELETE FROM perangkat WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    echo "Gagal menghapus perangkat.";
    exit;
}
$admin_id = $_SESSION['admin_id'];
$conn->query("INSERT INTO log_aktivitas (admin_id, aksi) 
              VALUES ($admin_id, 'Menghapus perangkat: $nama (Catatan: $catatan)')");

$stmt->close();

// Update status IP menjadi tersedia
$stmt = $conn->prepare("UPDATE ip_address SET status = 'Tersedia' WHERE ip = ?");
$stmt->bind_param("s", $ip);
$stmt->execute();
$stmt->close();

// Redirect ke halaman perangkat
header("Location: perangkat.php");
exit;
?>
