<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$result = $conn->query("SELECT * FROM ip_address ORDER BY ip ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar IP Address</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 p-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Daftar IP Address</h1>
        <a href="tambah_ip.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
            + Tambah IP
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-sm font-medium text-left">No</th>
                    <th class="px-6 py-3 text-sm font-medium text-left">IP Address</th>
                    <th class="px-6 py-3 text-sm font-medium text-left">Status</th>
                    <th class="px-6 py-3 text-sm font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><?= $no++ ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['ip']) ?></td>
                    <td class="px-6 py-4">
                        <span class="<?= $row['status'] === 'Tersedia' ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <?php if ($row['status'] === 'Tersedia'): ?>
                            <a href="edit_ip.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                            <a href="hapus_ip.php?ip=<?= urlencode($row['ip']) ?>" onclick="return confirm('Hapus IP ini?')" class="text-red-600 hover:underline">Hapus</a>
                        <?php else: ?>
                            <span class="text-gray-400 italic">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
