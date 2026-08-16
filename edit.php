<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include "koneksi.php";

$id = $_GET['id'];
$query = "SELECT * FROM notes WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>

<link rel="stylesheet" href="assets/css/style.css">

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <input type="text" name="title" value="<?php echo $row['title']; ?>">
    <br>
    <textarea name="content"><?php echo $row['content']; ?></textarea>
    <br>
    <button type="submit">Update Catatan</button>
    <a href="index.php">Kembali</a>
</form>