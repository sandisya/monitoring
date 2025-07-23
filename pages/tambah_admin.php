<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = $conn->query("SELECT id FROM admin WHERE username = '$username'");
    if ($cek->num_rows > 0) {
        $error = "Username sudah digunakan.";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        header("Location: admin.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Admin</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <body class="relative bg-gray-100">
    <style>
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: url(../bg.jpg);
            background-size: cover;
            background-position: center;
            filter: blur(2px); /* Atur seberapa blur */
            z-index: -1; /* Letakkan di belakang konten */
        }
    </style>


<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 p-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-center">Tambah Admin Baru</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-medium mb-1">Username</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label class="block font-medium mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition duration-200">
                Simpan Admin
            </button>
        </form>
    </div>
</div>

</body>
</html>
