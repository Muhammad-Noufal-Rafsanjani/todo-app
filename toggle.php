<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include "includes/koneksi.php";

$id = $_GET['id'];

$query = "UPDATE notes SET is_done = NOT is_done WHERE id = '$id'";
mysqli_query($conn, $query);

header("Location: index.php");
exit();
?>