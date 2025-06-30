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
$data = $conn->query("SELECT * FROM ip_address WHERE id = $id")->fetch_assoc();
if (!$data) {
    echo "Data tidak ditemukan."; exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip_baru = $_POST['ip'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE ip_address SET ip = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $ip_baru, $status, $id);
    $stmt->execute();

    header("Location: ip_address.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit IP</title></head>
<body>
    <h2>Edit IP Address</h2>
    <form method="POST">
        <label>IP Address:</label><br>
        <input type="text" name="ip" value="<?= $data['ip'] ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option value="Tersedia" <?= $data['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
            <option value="Digunakan" <?= $data['status'] == 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
        </select><br><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
