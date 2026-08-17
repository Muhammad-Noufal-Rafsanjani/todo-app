<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include "includes/koneksi.php";

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$query = "UPDATE notes SET title = '$title', content = '$content' WHERE id = '$id'";
mysqli_query($conn, $query);

header("Location: index.php");
exit();
?>