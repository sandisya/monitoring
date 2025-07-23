<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'];
    $jenis    = $_POST['jenis'];
    $lokasi   = $_POST['lokasi'];
    $ip       = $_POST['ip_address'];
    $mac      = $_POST['mac_address'];
    $status   = $_POST['status'];
    $tanggal  = $_POST['tanggal_instalasi'];
    $catatan  = $_POST['catatan'];

    $cek = $conn->prepare("SELECT COUNT(*) FROM ip_address WHERE ip = ? AND status = 'Tersedia'");
    $cek->bind_param("s", $ip);
    $cek->execute();
    $cek->bind_result($jumlah);
    $cek->fetch();
    $cek->close();

    if ($jumlah == 0) {
        echo "<script>alert('IP tidak tersedia atau sudah digunakan!'); window.location='tambah_perangkat.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO perangkat (nama, jenis_perangkat, lokasi, ip_address, mac_address, status, tanggal_instalasi, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nama, $jenis, $lokasi, $ip, $mac, $status, $tanggal, $catatan);

    $admin_id = $_SESSION['admin_id'];
    $conn->query("INSERT INTO log_aktivitas (admin_id, aksi) VALUES ($admin_id, 'Menambah perangkat: $nama (Catatan: $catatan)')");

    if ($stmt->execute()) {
        $update_ip = $conn->prepare("UPDATE ip_address SET status = 'Digunakan' WHERE ip = ?");
        $update_ip->bind_param("s", $ip);
        $update_ip->execute();
        $update_ip->close();

        header("Location: perangkat.php");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan perangkat.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Perangkat</title>
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
    <h1 class="text-2xl font-semibold mb-6">Tambah Perangkat</h1>

    <form method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-xl mx-auto space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Nama Perangkat</label>
            <input type="text" name="nama" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jenis Perangkat</label>
            <input type="text" name="jenis" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Lokasi</label>
            <input type="text" name="lokasi" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">IP Address</label>
            <select name="ip_address" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Pilih IP Tersedia --</option>
                <?php
                $result = $conn->query("SELECT ip FROM ip_address WHERE status = 'Tersedia'");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['ip']}'>{$row['ip']}</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">MAC Address</label>
            <input type="text" name="mac_address" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status Perangkat</label>
            <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Instalasi</label>
            <input type="date" name="tanggal_instalasi" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Catatan Tambahan</label>
            <textarea name="catatan" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
            Simpan Perangkat
        </button>

        <div class="text-center mt-4">
            <a href="perangkat.php" class="text-blue-600 hover:underline text-sm">← Kembali ke daftar perangkat</a>
        </div>
    </form>
</div>

</body>
</html>
