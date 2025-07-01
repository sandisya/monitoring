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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah IP Address</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6">Tambah IP Address</h2>

        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">IP Address</label>
                <input type="text" name="ip" required placeholder="contoh: 192.168.1.100"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                Simpan
            </button>
        </form>
    </div>
</div>

</body>
</html>
