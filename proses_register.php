<?php
session_start();
include "koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";
mysqli_query($conn, $query);

header("Location: login.php");
exit();
?>