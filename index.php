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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="navbar">
  <h2>To-Do List</h2>
  <div class="profile-menu">
    <div class="profile-trigger" onclick="toggleProfileDropdown()">
      <span class="profile-icon">👤</span>
      <span class="profile-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></span>
    </div>
  <div class="profile-dropdown" id="profileDropdown">
    <a href="#" onclick="showDeleteModal(); return false;"><i class="fa-solid fa-trash"></i> Delete Account</a>
    <a href="edit_profile.php"><i class="fa-solid fa-gear"></i> Edit Profile</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
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
<div class="search-bar">
    <div class="search-input-wrapper">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="keyword" name="keyword" placeholder="Search title or content..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
    </div>
    <input type="date" id="date" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
    <select id="status" name="status">
        <option value="">All</option>
        <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : ''; ?>>Done</option>
        <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : ''; ?>>Not Done</option>
    </select>
    <a href="index.php" class="btn-clear">Clear</a>
</div>
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
echo "<a href='edit.php?id=" . $row['id'] . "' class='icon-edit'><i class='fa-solid fa-pen'></i></a>";
echo "<a href='hapus.php?id=" . $row['id'] . "' class='icon-delete'><i class='fa-solid fa-trash'></i></a>";
echo "</div></div></div>";
}
?>
</div>

<div class="modal-overlay" id="deleteModalOverlay">
    <div class="modal-box">
        <h3>Hapus Akun?</h3>
        <p>Aksi ini permanen. Semua catatan Anda akan dihapus.</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="hideDeleteModal()">Batal</button>
            <form action="process_delete_account.php" method="POST" style="display: inline;">
                <button type="submit" class="btn-danger">Ya, Hapus Akun</button>
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