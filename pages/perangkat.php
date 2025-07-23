<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$search = $_GET['search'] ?? '';
$filter_jenis = $_GET['jenis'] ?? '';
$filter_status = $_GET['status'] ?? '';

$sql = "SELECT * FROM perangkat WHERE 1";
if ($search !== '') {
    $sql .= " AND (nama LIKE '%$search%' OR ip_address LIKE '%$search%' OR lokasi LIKE '%$search%')";
}
if ($filter_jenis !== '') {
    $sql .= " AND jenis_perangkat = '$filter_jenis'";
}
if ($filter_status !== '') {
    $sql .= " AND status = '$filter_status'";
}

$result = $conn->query($sql);
$jenisList = $conn->query("SELECT DISTINCT jenis_perangkat FROM perangkat");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Perangkat</title>
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

<div class="ml-64 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white mb-8 border border-blue-600 bg-blue-500 p-4 rounded-lg shadow">
    Data Perangkat
</h1>

        <a href="tambah_perangkat.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            + Tambah Perangkat
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6 flex flex-wrap gap-4 items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama / IP / lokasi"
               class="border border-gray-300 px-3 py-2 rounded w-full sm:w-1/4">
        
        <select name="jenis" class="border border-gray-300 px-3 py-2 rounded w-full sm:w-1/5">
            <option value="">-- Jenis Perangkat --</option>
            <?php while ($j = $jenisList->fetch_assoc()): ?>
                <option value="<?= $j['jenis_perangkat'] ?>" <?= $filter_jenis == $j['jenis_perangkat'] ? 'selected' : '' ?>>
                    <?= $j['jenis_perangkat'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="status" class="border border-gray-300 px-3 py-2 rounded w-full sm:w-1/5">
            <option value="">-- Status --</option>
            <option value="Aktif" <?= $filter_status == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="Tidak Aktif" <?= $filter_status == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Filter
        </button>
    </form>

    <!-- Tabel Data -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-center">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Jenis</th>
                    <th class="py-3 px-4">IP</th>
                    <th class="py-3 px-4">MAC</th>
                    <th class="py-3 px-4">Lokasi</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Instalasi</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $no++ ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $row['nama'] ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $row['jenis_perangkat'] ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $row['ip_address'] ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $row['mac_address'] ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $row['lokasi'] ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            <?= $row['status'] == 'Aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td style="font-size: 15px;" class="py-2 px-4"><?= $row['tanggal_instalasi'] ?></td>
                    <td style="font-size: 15px;" class="py-2 px-4">
                        <a href="edit_perangkat.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="hapus_perangkat.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus perangkat ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
