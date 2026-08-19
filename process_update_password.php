<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/koneksi.php";

$user_id = $_SESSION['user_id'];
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];

$query = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
mysqli_stmt_bind_param($query, "i", $user_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($currentPassword, $user['password'])) {
    header("Location: edit_profile.php?password_error=1");
    exit();
}

$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$updateQuery = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
mysqli_stmt_bind_param($updateQuery, "si", $hashedNewPassword, $user_id);
mysqli_stmt_execute($updateQuery);

header("Location: edit_profile.php?password_success=1");
exit();
?>