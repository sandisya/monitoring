<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="icon" href="../logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

<?php include '../includes/sidebar.php'; ?>

<div class="ml-64 p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Admin</h1>

    <?php
    $totalPerangkat = $conn->query("SELECT COUNT(*) as total FROM perangkat")->fetch_assoc()['total'];
    $ipDigunakan = $conn->query("SELECT COUNT(*) as total FROM ip_address WHERE status = 'Digunakan'")->fetch_assoc()['total'];
    $ipTersedia = $conn->query("SELECT COUNT(*) as total FROM ip_address WHERE status = 'Tersedia'")->fetch_assoc()['total'];
    $aktif = $conn->query("SELECT COUNT(*) as total FROM perangkat WHERE status = 'Aktif'")->fetch_assoc()['total'];
    $nonaktif = $conn->query("SELECT COUNT(*) as total FROM perangkat WHERE status = 'Tidak Aktif'")->fetch_assoc()['total'];
    ?>

    <!-- Cards Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h2 class="text-gray-600 text-lg font-semibold">Total Perangkat</h2>
            <p class="text-blue-600 text-3xl mt-2"><?= $totalPerangkat ?></p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h2 class="text-gray-600 text-lg font-semibold">IP Digunakan</h2>
            <p class="text-blue-600 text-3xl mt-2"><?= $ipDigunakan ?></p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h2 class="text-gray-600 text-lg font-semibold">IP Tersedia</h2>
            <p class="text-blue-600 text-3xl mt-2"><?= $ipTersedia ?></p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h2 class="text-gray-600 text-lg font-semibold">Perangkat Aktif</h2>
            <p class="text-green-600 text-3xl mt-2"><?= $aktif ?></p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h2 class="text-gray-600 text-lg font-semibold">Perangkat Tidak Aktif</h2>
            <p class="text-red-600 text-3xl mt-2"><?= $nonaktif ?></p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-center text-lg font-semibold text-gray-700 mb-4">Status Perangkat</h3>
            <canvas id="perangkatChart"></canvas>
        </div>
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-center text-lg font-semibold text-gray-700 mb-4">Status IP Address</h3>
            <canvas id="ipChart"></canvas>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script>
const perangkatChart = new Chart(document.getElementById('perangkatChart'), {
    type: 'pie',
    data: {
        labels: ['Aktif', 'Tidak Aktif'],
        datasets: [{
            data: [<?= $aktif ?>, <?= $nonaktif ?>],
            backgroundColor: ['#22c55e', '#ef4444'],
        }]
    }
});

const ipChart = new Chart(document.getElementById('ipChart'), {
    type: 'bar',
    data: {
        labels: ['IP Digunakan', 'IP Tersedia'],
        datasets: [{
            label: 'Jumlah IP',
            data: [<?= $ipDigunakan ?>, <?= $ipTersedia ?>],
            backgroundColor: ['#0ea5e9', '#facc15']
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>
