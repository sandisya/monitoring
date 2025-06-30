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
<html>
<head>
    <title>Manajemen Admin</title>
    <style>
        table { width: 80%; margin: 20px auto; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #007bff; color: white; }
        .add-btn {
            margin: 20px; display: inline-block; padding: 10px;
            background: green; color: white; text-decoration: none; border-radius: 5px;
        }
        .btn {
            padding: 5px 10px;
            background: red; color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="content">
    <h2 style="text-align: center;">Manajemen Admin</h2>
    <a href="tambah_admin.php" class="add-btn">+ Tambah Admin</a>
    <table>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['username'] ?></td>
            <td>
                <a href="edit_admin.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="hapus_admin.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Hapus admin ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
