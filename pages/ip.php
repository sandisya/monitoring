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
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white mb-8 border border-blue-600 bg-blue-500 p-4 rounded-lg shadow">
    Daftar IP Address
</h1>

        <a href="tambah_ip.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
            + Tambah IP
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th style="font-size: 15px;" class="px-6 py-3 text-sm font-medium text-left">No</th>
                    <th style="font-size: 15px;" class="px-6 py-3 text-sm font-medium text-left">IP Address</th>
                    <th style="font-size: 15px;" class="px-6 py-3 text-sm font-medium text-left">Status</th>
                    <th style="font-size: 15px;" class="px-6 py-3 text-sm font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4"><?= $no++ ?></td>
                    <td style="font-size: 15px; font-weight: bold;" class="px-6 py-4"><?= htmlspecialchars($row['ip']) ?></td>
                    <td class="px-6 py-4">
                        <strong><span style="font-size: 15px;" class="<?= $row['status'] === 'Tersedia' ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $row['status'] ?>
                        </span></strong>
                    </td>
                    <td class="px-6 py-4 text-center space-x-2">
                       <strong> <?php if ($row['status'] === 'Tersedia'): ?>
                            <a href="edit_ip.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline" style="font-size: 15px;">Edit</a>
                            <a href="hapus_ip.php?ip=<?= urlencode($row['ip']) ?>" onclick="return confirm('Hapus IP ini?')" class="text-red-600 hover:underline" style="font-size: 15px;">Hapus</a>
                        <?php else: ?>
                            <span class="text-gray-400 italic">-</span>
                        <?php endif; ?></strong>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
