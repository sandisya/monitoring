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

$data = $conn->query("SELECT * FROM admin WHERE id = $id")->fetch_assoc();
if (!$data) {
    echo "Admin tidak ditemukan."; exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin SET username=?, password=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $hash, $id);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET username=? WHERE id=?");
        $stmt->bind_param("si", $username, $id);
    }

    $stmt->execute();
    header("Location: admin.php");
    exit;
}
?>

<form method="POST" style="max-width:400px;margin:30px auto;">
    <h3>Edit Admin</h3>
    <label>Username</label>
    <input type="text" name="username" value="<?= $data['username'] ?>" required><br><br>

    <label>Password Baru (kosongkan jika tidak diubah)</label>
    <input type="password" name="password"><br><br>

    <button type="submit">Simpan Perubahan</button>
</form>
