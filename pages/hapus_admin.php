<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan."; exit;
}

$id = $_GET['id'];

// Jangan biarkan admin menghapus dirinya sendiri (opsional)
if ($_SESSION['admin_id'] == $id) {
    echo "Tidak bisa menghapus diri sendiri.";
    exit;
}

$conn->query("DELETE FROM admin WHERE id = $id");

header("Location: admin.php");
exit;
?>
