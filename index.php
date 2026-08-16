<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include "koneksi.php";

$user_id = $_SESSION['user_id'];
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

$conditions = ["user_id = ?"];
$types = "i";
$params = [$user_id];

if ($keyword !== '') {
    $conditions[] = "(title LIKE ? OR content LIKE ?)";
    $searchTerm = "%$keyword%";
    $types .= "ss";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if ($date !== '') {
    $conditions[] = "DATE(created_at) = ?";
    $types .= "s";
    $params[] = $date;
}

$whereClause = implode(" AND ", $conditions);
$query = "SELECT * FROM notes WHERE $whereClause ORDER BY created_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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

<form method="GET" action="index.php">
    <input type="text" name="keyword" placeholder="Search title or content..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
    <input type="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
    <button type="submit">Search</button>
</form>

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