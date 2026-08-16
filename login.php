<?php
include "koneksi.php";
?>
<?php if (isset($_GET['error'])): ?>
  <p style="color:red;">Email atau password salah.</p>
<?php endif; ?>

<link rel="stylesheet" href="assets/css/style.css">

<form action="proses_login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <br>
    <input type="password" name="password" placeholder="Password" required>
    <br>
    <button type="submit">Login</button>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</form>