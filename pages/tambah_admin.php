<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = $conn->query("SELECT id FROM admin WHERE username = '$username'");
    if ($cek->num_rows > 0) {
        echo "Username sudah digunakan.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    header("Location: admin.php");
    exit;
}
?>

<form method="POST" style="max-width:400px;margin:30px auto;">
    <h3>Tambah Admin</h3>
    <label>Username</label>
    <input type="text" name="username" required><br><br>
    <label>Password</label>
    <input type="password" name="password" required><br><br>
    <button type="submit">Simpan</button>
</form>
