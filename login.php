<?php
include "includes/koneksi.php";
?>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Login</h2>
        <?php if (isset($_GET['error'])): ?>
            <p class="msg-error"><i class="fa-solid fa-circle-exclamation"></i> Email atau password salah.</p>
        <?php endif; ?>
        <form action="proses_login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn-primary">Login</button>
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </form>
    </div>
</div>
