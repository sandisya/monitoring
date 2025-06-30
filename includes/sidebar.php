<style>
    .sidebar {
        width: 200px;
        background-color: #333;
        color: #fff;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
    }

    .sidebar h2 {
        text-align: center;
        color: #fff;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 10px 20px;
    }

    .sidebar ul li a {
        color: #fff;
        text-decoration: none;
        display: block;
    }

    .sidebar ul li a:hover {
        background-color: #575757;
        border-radius: 5px;
    }

    .content {
        margin-left: 220px;
        padding: 20px;
    }
</style>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="perangkat.php">Manajemen Perangkat</a></li>
        <li><a href="ip.php">Manajemen IP Address</a></li>
        <li><a href="admin.php">Manajemen Admin</a></li> <!-- ✅ Menu ditambahkan -->
        <li><a href="laporan.php">Export Laporan</a></li>
        <li><a href="log_aktivitas.php">Log Aktivitas</a></li>
        <li><a href="logout.php" style="color: red;">Logout</a></li>
    </ul>
</div>
