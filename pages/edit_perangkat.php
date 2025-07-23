<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM perangkat WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data perangkat tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $lokasi = $_POST['lokasi'];
    $ip_baru = $_POST['ip_address'];
    $ip_lama = $_POST['ip_lama'];
    $mac = $_POST['mac_address'];
    $status = $_POST['status'];
    $tanggal = $_POST['tanggal_instalasi'];
    $catatan = $_POST['catatan'];

    if ($ip_baru !== $ip_lama) {
        $cek_ip = $conn->prepare("SELECT COUNT(*) FROM ip_address WHERE ip = ? AND status = 'Tersedia'");
        $cek_ip->bind_param("s", $ip_baru);
        $cek_ip->execute();
        $cek_ip->bind_result($jumlah);
        $cek_ip->fetch();
        $cek_ip->close();

        if ($jumlah == 0) {
            echo "<script>alert('IP baru tidak tersedia!'); window.location='edit_perangkat.php?id=$id';</script>";
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE perangkat SET nama=?, jenis_perangkat=?, lokasi=?, ip_address=?, mac_address=?, status=?, tanggal_instalasi=?, catatan=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $nama, $jenis, $lokasi, $ip_baru, $mac, $status, $tanggal, $catatan, $id);
    $stmt->execute();
    $stmt->close();

    $admin_id = $_SESSION['admin_id'];
    $conn->query("INSERT INTO log_aktivitas (admin_id, aksi) 
              VALUES ($admin_id, 'Mengupdate perangkat: $nama (Catatan: $catatan)')");

    if ($ip_baru !== $ip_lama) {
        $update_old = $conn->prepare("UPDATE ip_address SET status = 'Tersedia' WHERE ip = ?");
        $update_old->bind_param("s", $ip_lama);
        $update_old->execute();
        $update_old->close();

        $update_new = $conn->prepare("UPDATE ip_address SET status = 'Digunakan' WHERE ip = ?");
        $update_new->bind_param("s", $ip_baru);
        $update_new->execute();
        $update_new->close();
    }

    header("Location: perangkat.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Perangkat</title>
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
    <h1 class="text-2xl font-semibold mb-6">Edit Perangkat</h1>

    <form method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-xl mx-auto space-y-4">
        <input type="hidden" name="ip_lama" value="<?= htmlspecialchars($data['ip_address']) ?>">

        <div>
            <label class="block text-sm font-medium mb-1">Nama Perangkat</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jenis Perangkat</label>
            <input type="text" name="jenis" value="<?= htmlspecialchars($data['jenis_perangkat']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Lokasi</label>
            <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">IP Address</label>
            <select name="ip_address" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="<?= $data['ip_address'] ?>"><?= $data['ip_address'] ?> (Sedang digunakan)</option>
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
            <input type="text" name="mac_address" value="<?= htmlspecialchars($data['mac_address']) ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status Perangkat</label>
            <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="Aktif" <?= $data['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Tidak Aktif" <?= $data['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Instalasi</label>
            <input type="date" name="tanggal_instalasi" value="<?= $data['tanggal_instalasi'] ?>" required class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Catatan</label>
            <textarea name="catatan" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"><?= htmlspecialchars($data['catatan']) ?></textarea>
        </div>

        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded-lg transition">
            Simpan Perubahan
        </button>

        <div class="text-center mt-4">
            <a href="perangkat.php" class="text-blue-600 hover:underline text-sm">← Kembali ke daftar perangkat</a>
        </div>
    </form>
</div>

</body>
</html>
