<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include "includes/koneksi.php";
include "includes/get_notes.php";

$user_id = $_SESSION['user_id'];
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$result = getNotes($conn, $user_id, $keyword, $date, $status);

while ($row = mysqli_fetch_assoc($result)) {
    $doneClass = $row['is_done'] ? "done" : "";
    $checked = $row['is_done'] ? "checked" : "";
    echo "<div class='note-card $doneClass'>";
    echo "<input type='checkbox' $checked onclick=\"location.href='toggle.php?id=" . $row['id'] . "'\">";
    echo "<div class='note-content'>";
    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
    echo "<div class='note-actions'>";
    echo "<a href='edit.php?id=" . $row['id'] . "'>Edit</a>";
    echo "<a href='hapus.php?id=" . $row['id'] . "'>Hapus</a>";
    echo "</div></div></div>";
}
?>