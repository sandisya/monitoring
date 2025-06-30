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
<html>
<head>
    <title>Manajemen IP Address</title>
    <style>
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc; padding: 10px; text-align: center;
        }
        th {
            background: #007bff; color: white;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            background: red;
            color: white;
            border-radius: 5px;
        }
        a.add-btn {
            display: inline-block;
            margin: 20px;
            padding: 10px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="content">
    <h2 style="text-align: center;">Daftar IP Address</h2>

    <a href="tambah_ip.php" class="add-btn">+ Tambah IP</a>

    <table>
        <tr>
            <th>No</th>
            <th>IP Address</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['ip'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if ($row['status'] == 'Tersedia'): ?>
                    <a href="edit_ip.php?id=<?= $row['id'] ?>">Edit</a> 
                    <a href="hapus_ip.php?ip=<?= $row['ip'] ?>" class="btn" onclick="return confirm('Yakin hapus IP ini?')">Hapus</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
