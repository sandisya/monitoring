<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = $_POST['ip'];

    // Cek apakah IP sudah ada
    $cek = $conn->query("SELECT * FROM ip_address WHERE ip = '$ip'");
    if ($cek->num_rows > 0) {
        $error = "IP sudah terdaftar.";
    } else {
        $stmt = $conn->prepare("INSERT INTO ip_address (ip, status) VALUES (?, 'Tersedia')");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        header("Location: ip.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah IP Address</title>
    <style>
        form {
            max-width: 400px;
            margin: 30px auto;
            background: #fff; padding: 20px;
            box-shadow: 0 0 10px #ccc; border-radius: 10px;
        }
        input {
            width: 100%; padding: 10px; margin: 10px 0;
        }
        button {
            background: green; color: white;
            border: none; padding: 10px; width: 100%;
        }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="content">
    <h2 style="text-align:center;">Tambah IP Address</h2>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>IP Address</label>
        <input type="text" name="ip" required placeholder="contoh: 192.168.1.100">
        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
