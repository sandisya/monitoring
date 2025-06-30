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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit IP Address</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 flex items-center justify-center min-h-screen p-6">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6">Edit IP Address</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">IP Address</label>
                <input type="text" name="ip" value="<?= htmlspecialchars($data['ip']) ?>" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block mb-1 font-medium">Status</label>
                <select name="status"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Tersedia" <?= $data['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Digunakan" <?= $data['status'] == 'Digunakan' ? 'selected' : '' ?>>Digunakan</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

</body>
</html>
