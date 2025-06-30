<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit;
}

$id = intval($_GET['id']);

// Ambil data perangkat berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM perangkat WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data perangkat tidak ditemukan.";
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = $_POST['nama'];
    $jenis     = $_POST['jenis'];
    $lokasi    = $_POST['lokasi'];
    $ip_baru   = $_POST['ip_address'];
    $ip_lama   = $_POST['ip_lama'];
    $mac       = $_POST['mac_address'];
    $status    = $_POST['status'];
    $tanggal   = $_POST['tanggal_instalasi'];
    $catatan   = $_POST['catatan'];

    // Jika IP berubah, cek apakah IP baru tersedia
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

    // Update data perangkat
    $stmt = $conn->prepare("UPDATE perangkat SET nama=?, jenis_perangkat=?, lokasi=?, ip_address=?, mac_address=?, status=?, tanggal_instalasi=?, catatan=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $nama, $jenis, $lokasi, $ip_baru, $mac, $status, $tanggal, $catatan, $id);
    $stmt->execute();
    $admin_id = $_SESSION['admin_id'];
    $conn->query("INSERT INTO log_aktivitas (admin_id, aksi) 
              VALUES ($admin_id, 'Mengupdate perangkat: $nama (Catatan: $catatan)')");

    $stmt->close();

    // Update status IP jika berubah
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
<html>
<head>
    <title>Edit Perangkat</title>
    <style>
        form {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            background: orange;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
        }
        a.back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="content">
    <h2 style="text-align: center;">Edit Perangkat</h2>

    <form method="POST">
        <input type="hidden" name="ip_lama" value="<?= htmlspecialchars($data['ip_address']) ?>">

        <label>Nama Perangkat</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label>Jenis Perangkat</label>
        <input type="text" name="jenis" value="<?= htmlspecialchars($data['jenis_perangkat']) ?>" required>

        <label>Lokasi</label>
        <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>

        <label>IP Address</label>
        <select name="ip_address" required>
            <option value="<?= $data['ip_address'] ?>"><?= $data['ip_address'] ?> (Sedang digunakan)</option>
            <?php
            $result = $conn->query("SELECT ip FROM ip_address WHERE status = 'Tersedia'");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['ip']}'>{$row['ip']}</option>";
            }
            ?>
        </select>

        <label>MAC Address</label>
        <input type="text" name="mac_address" value="<?= htmlspecialchars($data['mac_address']) ?>" required>

        <label>Status</label>
        <select name="status">
            <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="Tidak Aktif" <?= $data['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>

        <label>Tanggal Instalasi</label>
        <input type="date" name="tanggal_instalasi" value="<?= $data['tanggal_instalasi'] ?>" required>

        <label>Catatan</label>
        <textarea name="catatan"><?= htmlspecialchars($data['catatan']) ?></textarea>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <a class="back" href="perangkat.php">← Kembali ke daftar perangkat</a>
</div>

</body>
</html>
