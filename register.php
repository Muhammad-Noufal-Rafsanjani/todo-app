<?php
include "includes/koneksi.php";
?>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="auth-wrapper">
    <div class="auth-card">
         <h2>Register</h2>
      <form action="proses_register.php" method="POST">
         <input type="email" name="email" placeholder="Email" required>
         <input type="password" name="password" placeholder="Password" required>
         <button type="submit" class="btn-primary">Daftar</button>
         <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
      </form>
   </div>
</div>
