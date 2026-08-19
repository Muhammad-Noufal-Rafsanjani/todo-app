<?php
session_start();

include "includes/koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=1");
}
?>