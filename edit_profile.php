<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">

<div class="container">
    <div class="navbar">
        <h2>Edit Profile</h2>
        <button onclick="window.location.href='index.php'" class="btn-primary" style="text-decoration: none; padding: 8px 16px;">Kembali ke Catatanku</button>
    </div>

    <div class="edit-profile-layout">
        <div class="edit-profile-form">

            <?php if (isset($_GET['email_success'])): ?>
                <p class="msg-success">Email updated successfully.</p>
            <?php endif; ?>
            <?php if (isset($_GET['email_error'])): ?>
                <p class="msg-error">Email is already taken or invalid.</p>
            <?php endif; ?>
            <?php if (isset($_GET['password_success'])): ?>
                <p class="msg-success">Password updated successfully.</p>
            <?php endif; ?>
            <?php if (isset($_GET['password_error'])): ?>
                <p class="msg-error">Current password is incorrect.</p>
            <?php endif; ?>

            <h3>Perbarui Email</h3>
            <form action="process_update_email.php" method="POST">
                <input type="email" name="new_email" placeholder="New email" required>
                <button type="submit" class="btn-primary">Perbarui Email</button>
            </form>

            <h3>Perbarui Kata Sandi</h3>
            <form action="process_update_password.php" method="POST">
                <input type="password" name="current_password" placeholder="Current password" required>
                <input type="password" name="new_password" placeholder="New password" required>
                <button type="submit" class="btn-primary">Perbarui Kata Sandi</button>
            </form>
        </div>

        <div class="profile-info-card">
            <div class="profile-avatar"><i class="fa-solid fa-user"></i></div>
            <h4><?php echo htmlspecialchars(explode('@', $_SESSION['user_email'] ?? '')[0]); ?></h4>
            <p><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
        </div>
    </div>
</div>