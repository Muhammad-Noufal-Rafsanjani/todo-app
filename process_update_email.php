<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/koneksi.php";

$user_id = $_SESSION['user_id'];
$newEmail = trim($_POST['new_email']);

$checkQuery = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
mysqli_stmt_bind_param($checkQuery, "si", $newEmail, $user_id);
mysqli_stmt_execute($checkQuery);
$checkResult = mysqli_stmt_get_result($checkQuery);

if (mysqli_fetch_assoc($checkResult)) {
    header("Location: edit_profile.php?email_error=1");
    exit();
}

$updateQuery = mysqli_prepare($conn, "UPDATE users SET email = ? WHERE id = ?");
mysqli_stmt_bind_param($updateQuery, "si", $newEmail, $user_id);
mysqli_stmt_execute($updateQuery);

$_SESSION['user_email'] = $newEmail;

header("Location: edit_profile.php?email_success=1");
exit();
?>