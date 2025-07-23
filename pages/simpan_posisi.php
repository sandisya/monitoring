<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

include '../includes/db.php';
$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $id => $pos) {
    $id = (int)$id;
    $x = $pos['x'];
    $y = $pos['y'];
    $stmt = $conn->prepare("UPDATE perangkat SET pos_x = ?, pos_y = ? WHERE id = ?");
    $stmt->bind_param("ddi", $x, $y, $id);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['success' => true]);
