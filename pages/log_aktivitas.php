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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas</title>
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
    <h1 class="text-3xl font-bold text-white mb-8 border border-blue-600 bg-blue-500 p-4 rounded-lg shadow">
    Log Aktivitas Admin
</h1>


    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th style="font-size: 15px;" class="px-4 py-3 text-left text-sm font-medium">No</th>
                    <th style="font-size: 15px;" class="px-4 py-3 text-left text-sm font-medium">Admin</th>
                    <th style="font-size: 15px;" class="px-4 py-3 text-left text-sm font-medium">Aksi</th>
                    <th style="font-size: 15px;" class="px-4 py-3 text-left text-sm font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style="font-size: 18px;" class="px-4 py-2"><?= $no++ ?></td>
                    <td style="font-size: 18px;" class="px-4 py-2"><?= htmlspecialchars($row['username']) ?></td>
                    <td style="font-size: 18px;" class="px-4 py-2"><?= htmlspecialchars($row['aksi']) ?></td>
                    <td style="font-size: 18px;" class="px-4 py-2"><?= $row['waktu'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
