<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="navbar">
    <h2>Edit Profile</h2>
    <a href="index.php">Back to Notes</a>
</div>

<?php if (isset($_GET['email_success'])): ?>
    <p style="color: green;">Email updated successfully.</p>
<?php endif; ?>
<?php if (isset($_GET['email_error'])): ?>
    <p style="color: red;">Email is already taken or invalid.</p>
<?php endif; ?>
<?php if (isset($_GET['password_success'])): ?>
    <p style="color: green;">Password updated successfully.</p>
<?php endif; ?>
<?php if (isset($_GET['password_error'])): ?>
    <p style="color: red;">Current password is incorrect.</p>
<?php endif; ?>

<h3>Update Email</h3>
<form action="process_update_email.php" method="POST">
    <input type="email" name="new_email" placeholder="New email" required>
    <button type="submit">Update Email</button>
</form>

<h3>Update Password</h3>
<form action="process_update_password.php" method="POST">
    <input type="password" name="current_password" placeholder="Current password" required>
    <input type="password" name="new_password" placeholder="New password" required>
    <button type="submit">Update Password</button>
</form>
