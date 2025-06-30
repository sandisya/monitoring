<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: /pages/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { padding: 10px; width: 100%; background: #007bff; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>
    <form method="POST" action="../actions/login_action.php">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Masuk</button>
    </form>
</div>

</body>
</html>
