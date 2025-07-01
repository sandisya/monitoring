<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$result = $conn->query("SELECT * FROM admin ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Admin</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 p-8">
    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-center mb-6">Manajemen Admin</h2>

        <div class="flex justify-end mb-4">
            <a href="tambah_admin.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                + Tambah Admin
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th style="font-size: 15px;" class="px-4 py-2 border">No</th>
                        <th style="font-size: 15px;" class="px-4 py-2 border">Username</th>
                        <th style="font-size: 15px;" class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td style="font-size: 18px; font-weight: bold;" class="px-4 py-2 border text-center"><?= $no++ ?></td>
                        <td style="font-size: 18px; font-weight: bold;" class="px-4 py-2 border text-center"><?= htmlspecialchars($row['username']) ?></td>
                        <td style="font-size: 18px; font-weight: bold;" class="px-4 py-2 border text-center">
                            <a style="font-size: 18px;" href="edit_admin.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <a style="font-size: 18px;" href="hapus_admin.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline"
                               onclick="return confirm('Hapus admin ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
