<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM admin WHERE id = $id")->fetch_assoc();
if (!$data) {
    echo "Admin tidak ditemukan.";
    exit;
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 p-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-center">Edit Admin</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-medium mb-1">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label class="block font-medium mb-1">Password Baru (biarkan kosong jika tidak ingin diubah)</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition duration-200">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

</body>
</html>
