<?php
session_start();

include "koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (email, password) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_password);
mysqli_stmt_execute($stmt);

header("Location: login.php");
exit();
?>