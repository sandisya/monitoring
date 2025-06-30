<!-- Tambahkan di file layout atau di setiap halaman -->
<!-- Pastikan Tailwind sudah tersedia -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Sidebar -->
<div class="fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white shadow-lg flex flex-col">
  <div class="text-center py-6 border-b border-gray-700">
    <h2 class="text-xl font-bold">Admin Panel</h2>
  </div>
  <ul class="flex-1 px-4 py-6 space-y-2">
    <li>
      <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">📊 Dashboard</a>
    </li>
    <li>
      <a href="perangkat.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">💻 Manajemen Perangkat</a>
    </li>
    <li>
      <a href="ip.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">🌐 Manajemen IP Address</a>
    </li>
    <li>
      <a href="admin.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">👤 Manajemen Admin</a>
    </li>
    <li>
      <a href="laporan.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">📁 Export Laporan</a>
    </li>
    <li>
      <a href="log_aktivitas.php" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">🕒 Log Aktivitas</a>
    </li>
    <!-- <li>
      <a href="logout.php" class="block px-4 py-2 text-red-400 hover:text-red-300 transition">🚪 Logout</a>
    </li> -->
    <div class="px-4 py-6">
        <button id="logoutBtn" class="block w-full text-center bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
            Logout
        </button>
    </div>
  </ul>
    


<script>
document.getElementById('logoutBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Logout?',
        text: "Apakah Anda yakin ingin keluar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    })
});
</script>


</div>

<!-- Konten utama bergeser -->
<div class="ml-64 p-6">
  <!-- Konten halaman di sini -->
</div>
