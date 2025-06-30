<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

// Ambil filter dari form
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Query dasar
$sql = "SELECT * FROM perangkat WHERE 1";

// Tambah filter
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

// Ambil semua jenis unik untuk dropdown
$jenisList = $conn->query("SELECT DISTINCT jenis_perangkat FROM perangkat");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Perangkat</title>
    <style>
        table {
            width: 95%; margin: 20px auto; border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc; padding: 8px; text-align: center;
        }
        th {
            background: #007bff; color: white;
        }
        form.filter {
            width: 95%; margin: 20px auto;
            display: flex; gap: 10px; align-items: center;
            flex-wrap: wrap;
        }
        input, select, button {
            padding: 8px;
        }
        .aksi a {
            margin: 0 5px;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="content">
    <h2 style="text-align: center;">Data Perangkat</h2>
    <div style="text-align:left; margin-bottom: 10px;">
    <a href="tambah_perangkat.php" style="padding: 8px 15px; background: green; color: white; text-decoration: none; border-radius: 5px;">+ Tambah Perangkat</a>
</div>


    <form class="filter" method="GET">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama/IP/lokasi">
        
        <select name="jenis">
            <option value="">-- Jenis Perangkat --</option>
            <?php while ($j = $jenisList->fetch_assoc()): ?>
                <option value="<?= $j['jenis_perangkat'] ?>" <?= ($filter_jenis == $j['jenis_perangkat']) ? 'selected' : '' ?>>
                    <?= $j['jenis_perangkat'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="status">
            <option value="">-- Status --</option>
            <option value="Aktif" <?= ($filter_status == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
            <option value="Tidak Aktif" <?= ($filter_status == 'Tidak Aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
        </select>

        <button type="submit">Filter</button>
    </form>
    

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>IP</th>
            <th>MAC</th>
            <th>Lokasi</th>
            <th>Status</th>
            <th>Tanggal Instalasi</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['jenis_perangkat'] ?></td>
            <td><?= $row['ip_address'] ?></td>
            <td><?= $row['mac_address'] ?></td>
            <td><?= $row['lokasi'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['tanggal_instalasi'] ?></td>
            <td class="aksi">
                <a href="edit_perangkat.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="hapus_perangkat.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus perangkat ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
