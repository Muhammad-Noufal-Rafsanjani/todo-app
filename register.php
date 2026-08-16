<?php
include "koneksi.php";
?>

<link rel="stylesheet" href="assets/css/style.css">

<form action="proses_register.php" method="POST">
   <input type="email" name="email" placeholder="Email" required>
   <br>
   <input type="password" name="password" placeholder="Password" required>
   <br>
   <button type="submit">Daftar</button>
   <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</form>
