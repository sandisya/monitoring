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
  <link rel="icon" href="../logo.png" type="image/png">
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


<div class="ml-64 p-8">
  <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md text-center">
    <h1 class="text-3xl font-bold text-white mb-8 border border-blue-600 bg-blue-500 p-4 rounded-lg shadow">
    Export Laporan
</h1>


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
