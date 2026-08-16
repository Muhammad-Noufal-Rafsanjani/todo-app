<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include "koneksi.php";

$title = $_POST['title'];
$content = $_POST['content'];
$user_id = $_SESSION['user_id'];

$query = "INSERT INTO notes (title, content, user_id) VALUES ('$title', '$content', '$user_id')";
mysqli_query($conn, $query);

header("Location: index.php");
exit();
?>