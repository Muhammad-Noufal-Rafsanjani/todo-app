<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/koneksi.php";

$user_id = $_SESSION['user_id'];

$deleteNotes = mysqli_prepare($conn, "DELETE FROM notes WHERE user_id = ?");
mysqli_stmt_bind_param($deleteNotes, "i", $user_id);
mysqli_stmt_execute($deleteNotes);

$deleteUser = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($deleteUser, "i", $user_id);
mysqli_stmt_execute($deleteUser);

session_destroy();
header("Location: login.php");
exit();
?>