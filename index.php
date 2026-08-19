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
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
  <h2>Catatanku</h2>
  <div class="profile-menu">
    <div class="profile-trigger" onclick="toggleProfileDropdown()">
      <span class="profile-icon">👤</span>
      <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></span>
    </div>
    <div class="profile-dropdown" id="profileDropdown">
      <a href="edit_profile.php">Edit Profile</a>
      <a href="#" onclick="showDeleteModal(); return false;">Delete Account</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
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
    <input type="text" id="keyword" name="keyword" placeholder="Search title or content..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
    <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
    <select id="status" name="status">
        <option value="">All</option>
        <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : ''; ?>>Done</option>
        <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : ''; ?>>Not Done</option>
    </select>
    <button type="submit">Search</button>
    <a href="index.php">Clear</a>
</form>

<div id="notesList">
<?php
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
</div>

<div class="modal-overlay" id="deleteModalOverlay">
    <div class="modal-box">
        <h3>Delete Account?</h3>
        <p>This action is permanent. All your notes will be deleted and cannot be recovered.</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="hideDeleteModal()">Cancel</button>
            <form action="process_delete_account.php" method="POST" style="display: inline;">
                <button type="submit" class="btn-danger">Yes, Delete My Account</button>
            </form>
        </div>
    </div>
</div>

<script>
let debounceTimer;

function fetchNotes() {
    const keyword = document.getElementById('keyword').value;
    const date = document.getElementById('date').value;
    const status = document.getElementById('status').value;

    const params = new URLSearchParams({ keyword, date, status });

    fetch('search_notes.php?' + params.toString())
        .then(response => response.text())
        .then(html => {
            document.getElementById('notesList').innerHTML = html;
        })
        .catch(error => console.error('Error fetching notes:', error));
}

function handleInput() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchNotes, 500);
}

function toggleProfileDropdown() {
    document.getElementById('profileDropdown').classList.toggle('show');
}

document.addEventListener('click', function(event) {
    const menu = document.querySelector('.profile-menu');
    const dropdown = document.getElementById('profileDropdown');
    if (menu && !menu.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

function showDeleteModal() {
    document.getElementById('deleteModalOverlay').classList.add('show');
}

function hideDeleteModal() {
    document.getElementById('deleteModalOverlay').classList.remove('show');
}

document.getElementById('keyword').addEventListener('input', handleInput);
document.getElementById('date').addEventListener('input', fetchNotes);
document.getElementById('status').addEventListener('change', fetchNotes);
</script>