<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

if (!isset($_GET['ip'])) {
    echo "IP tidak ditemukan.";
    exit;
}

$ip = $_GET['ip'];
// Cek status dulu
$cek = $conn->query("SELECT status FROM ip_address WHERE ip = '$ip'");
$data = $cek->fetch_assoc();

if (!$data) {
    echo "IP tidak valid.";
    exit;
}
if ($data['status'] !== 'Tersedia') {
    echo "IP sedang digunakan dan tidak bisa dihapus.";
    exit;
}

$conn->query("DELETE FROM ip_address WHERE ip = '$ip'");
header("Location: ip.php");
exit;
?>
