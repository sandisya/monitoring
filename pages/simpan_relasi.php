<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include '../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);

$dari = intval($data['from']);
$ke = intval($data['to']);

// Cek apakah sudah ada relasi yang sama
$cek = $conn->prepare("SELECT COUNT(*) FROM relasi_perangkat WHERE dari_id = ? AND ke_id = ?");
$cek->bind_param("ii", $dari, $ke);
$cek->execute();
$cek->bind_result($jumlah);
$cek->fetch();
$cek->close();

if ($jumlah > 0) {
    echo json_encode(['success' => false, 'message' => 'Relasi sudah ada.']);
    exit;
}

// Simpan relasi baru
$stmt = $conn->prepare("INSERT INTO relasi_perangkat (dari_id, ke_id) VALUES (?, ?)");
$stmt->bind_param("ii", $dari, $ke);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan ke database.']);
}
$stmt->close();
