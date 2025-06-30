<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
include '../includes/sidebar.php';
?>

<div class="content">
  <h2 style="text-align:center;">Export Laporan Perangkat</h2>

  <div style="text-align:center; margin:30px;">
    <a href="../exports/export_pdf.php" style="padding:10px 20px; background:#d9534f; color:#fff; text-decoration:none; border-radius:5px;">
      Export PDF
    </a>
    &nbsp;
    <a href="../exports/export_excel.php" style="padding:10px 20px; background:#5bc0de; color:#fff; text-decoration:none; border-radius:5px;">
      Export Excel
    </a>
  </div>
</div>
