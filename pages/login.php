<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: /pages/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-blue-100 via-white to-purple-100 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-2xl rounded-2xl flex max-w-4xl w-full overflow-hidden">
    
    <!-- Left Side (Image/Illustration) -->
    <div class="hidden md:flex items-center justify-center w-1/2 bg-gradient-to-tr from-blue-600 to-purple-600 p-10">
      <div class="text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Selamat Datang</h2>
        <p class="text-sm opacity-90">Silakan login untuk mengakses dashboard monitoring perangkat.</p>
        <img src="https://cdni.iconscout.com/illustration/premium/thumb/user-login-6299866-5295188.png" alt="Login Illustration" class="w-64 mt-6 mx-auto">
      </div>
    </div>

    <!-- Right Side (Form) -->
    <div class="w-full md:w-1/2 p-8 sm:p-12">
      <h2 class="text-3xl font-bold text-gray-800 text-center mb-6">Login Admin</h2>

      <form method="POST" action="../actions/login_action.php" class="space-y-5">
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700">Username</label>
          <input type="text" name="username" placeholder="Masukkan username" required autofocus
                 class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div>
          <label class="block mb-1 text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" placeholder="Masukkan password" required
                 class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-300">
          Masuk
        </button>
      </form>

      <p class="text-xs text-center text-gray-400 mt-6">&copy; <?= date("Y") ?> Sistem Monitoring. All rights reserved.</p>
    </div>

  </div>

</body>
</html>
