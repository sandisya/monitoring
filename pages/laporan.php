<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Export Laporan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<div class="ml-64 p-8">
  <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md text-center">
    <h2 class="text-2xl font-semibold mb-6">Export Laporan Perangkat</h2>

    <div class="flex justify-center gap-6">
      <a href="../exports/export_pdf.php"
         class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition duration-200 shadow">
        Export PDF
      </a>
      <a href="../exports/export_excel.php"
         class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-3 rounded-lg transition duration-200 shadow">
        Export Excel
      </a>
    </div>
  </div>
</div>

</body>
</html>
