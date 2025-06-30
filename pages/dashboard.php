<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="content">
    <h1>Dashboard Admin</h1>

    <?php
    // Ambil data ringkasan
    $totalPerangkat = $conn->query("SELECT COUNT(*) as total FROM perangkat")->fetch_assoc()['total'];
    $ipDigunakan = $conn->query("SELECT COUNT(*) as total FROM ip_address WHERE status = 'Digunakan'")->fetch_assoc()['total'];
    $ipTersedia = $conn->query("SELECT COUNT(*) as total FROM ip_address WHERE status = 'Tersedia'")->fetch_assoc()['total'];
    $aktif = $conn->query("SELECT COUNT(*) as total FROM perangkat WHERE status = 'Aktif'")->fetch_assoc()['total'];
    $nonaktif = $conn->query("SELECT COUNT(*) as total FROM perangkat WHERE status = 'Tidak Aktif'")->fetch_assoc()['total'];
    ?>

    <div class="cards">
        <div class="card"><h2>Total Perangkat</h2><p><?= $totalPerangkat ?></p></div>
        <div class="card"><h2>IP Digunakan</h2><p><?= $ipDigunakan ?></p></div>
        <div class="card"><h2>IP Tersedia</h2><p><?= $ipTersedia ?></p></div>
        <div class="card"><h2>Perangkat Aktif</h2><p><?= $aktif ?></p></div>
        <div class="card"><h2>Perangkat Tidak Aktif</h2><p><?= $nonaktif ?></p></div>
    </div>

    <!-- Tambahan Grafik -->
    <div style="width: 90%; margin: 40px auto; display: flex; flex-wrap: wrap; gap: 40px; justify-content: center;">
        <div style="width: 400px;">
            <h3 style="text-align:center;">Status Perangkat</h3>
            <canvas id="perangkatChart"></canvas>
        </div>
        <div style="width: 400px;">
            <h3 style="text-align:center;">Status IP Address</h3>
            <canvas id="ipChart"></canvas>
        </div>
    </div>

    <script>
    const perangkatChart = new Chart(document.getElementById('perangkatChart'), {
        type: 'pie',
        data: {
            labels: ['Aktif', 'Tidak Aktif'],
            datasets: [{
                data: [<?= $aktif ?>, <?= $nonaktif ?>],
                backgroundColor: ['#28a745', '#dc3545']
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
                backgroundColor: ['#17a2b8', '#ffc107']
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>
</div>

<style>
    .cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 250px;
        box-shadow: 0 0 10px #ccc;
        text-align: center;
    }

    .card h2 {
        font-size: 18px;
    }

    .card p {
        font-size: 24px;
        color: #007bff;
    }
</style>

</body>
</html>
