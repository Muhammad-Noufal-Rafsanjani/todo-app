<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include "koneksi.php";

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM notes WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
  <h2>Catatanku</h2>
  <a href="logout.php">Logout</a>
</div>

<form action="tambah.php" method="POST">
  <input type="text" name="title" placeholder="Judul catatan" required>
  <br>
  <textarea name="content" placeholder="Isi catatan"></textarea>
  <br>
  <button type="submit">Tambah Catatan</button>
</form>

<hr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
  $doneClass = $row['is_done'] ? "done" : "";
  $checked = $row['is_done'] ? "checked" : "";
  echo "<div class='note-card $doneClass'>";
  echo "<input type='checkbox' $checked onclick=\"location.href='toggle.php?id=" . $row['id'] . "'\">";
  echo "<div class='note-content'>";
  echo "<h3>" . $row['title'] . "</h3>";
  echo "<p>" . $row['content'] . "</p>";
  echo "<div class='note-actions'>";
  echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a>";
  echo "<a href='hapus.php?id=" . $row['id'] . "'>Hapus</a>";
  echo "</div></div></div>";
}
?>