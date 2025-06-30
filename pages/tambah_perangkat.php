<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

// Tangani form saat disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'];
    $jenis    = $_POST['jenis'];
    $lokasi   = $_POST['lokasi'];
    $ip       = $_POST['ip_address'];
    $mac      = $_POST['mac_address'];
    $status   = $_POST['status'];
    $tanggal  = $_POST['tanggal_instalasi'];
    $catatan  = $_POST['catatan'];

    // Cek apakah IP yang dipilih masih tersedia
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

    // Simpan ke tabel perangkat
    $stmt = $conn->prepare("INSERT INTO perangkat (nama, jenis_perangkat, lokasi, ip_address, mac_address, status, tanggal_instalasi, catatan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssss", $nama, $jenis, $lokasi, $ip, $mac, $status, $tanggal, $catatan);
    $admin_id = $_SESSION['admin_id'];
    $conn->query("INSERT INTO log_aktivitas (admin_id, aksi) VALUES ($admin_id, 'Menambah perangkat: $nama (Catatan: $catatan)')");


    if ($stmt->execute()) {
        // Update status IP
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
<html>
<head>
    <title>Tambah Perangkat</title>
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
            background: green;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
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
    <h2 style="text-align: center;">Tambah Perangkat</h2>

    <form method="POST">
        <label>Nama Perangkat</label>
        <input type="text" name="nama" required>

        <label>Jenis Perangkat</label>
        <input type="text" name="jenis" required>

        <label>Lokasi</label>
        <input type="text" name="lokasi" required>

        <label>IP Address</label>
        <select name="ip_address" required>
            <option value="">-- Pilih IP Tersedia --</option>
            <?php
            $result = $conn->query("SELECT ip FROM ip_address WHERE status = 'Tersedia'");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['ip']}'>{$row['ip']}</option>";
            }
            ?>
        </select>

        <label>MAC Address</label>
        <input type="text" name="mac_address" required>

        <label>Status Perangkat</label>
        <select name="status" required>
            <option value="Aktif">Aktif</option>
            <option value="Tidak Aktif">Tidak Aktif</option>
        </select>

        <label>Tanggal Instalasi</label>
        <input type="date" name="tanggal_instalasi" required>

        <label>Catatan Tambahan</label>
        <textarea name="catatan"></textarea>

        <button type="submit">Simpan</button>
    </form>

    <a class="back" href="perangkat.php">← Kembali ke daftar perangkat</a>
</div>

</body>
</html>
