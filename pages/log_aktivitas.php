<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

$result = $conn->query("
    SELECT log_aktivitas.*, admin.username 
    FROM log_aktivitas 
    JOIN admin ON admin.id = log_aktivitas.admin_id 
    ORDER BY waktu DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log Aktivitas</title>
    <style>
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc; padding: 10px; text-align: left;
        }
        th {
            background: #007bff; color: white;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="content">
    <h2 style="text-align:center;">Log Aktivitas Admin</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Admin</th>
            <th>Aksi</th>
            <th>Waktu</th>
        </tr>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['aksi']) ?></td>
            <td><?= $row['waktu'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
