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

<div class="container">
    <div class="navbar">
        <h2><i class="fa-solid fa-note-sticky"></i> To-Do List</h2>
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

<?php
$totalNotes = mysqli_num_rows($result);
mysqli_data_seek($result, 0);
$doneCount = 0;
$tempResult = getNotes($conn, $user_id, '', '', '1');
$doneCount = mysqli_num_rows($tempResult);
?>

<div class="stats-bar">
    <div class="stat-item">
        <span class="stat-number"><?php echo $totalNotes; ?></span>
        <span class="stat-label">Total Catatan</span>
    </div>
    <div class="stat-item">
        <span class="stat-number"><?php echo $doneCount; ?></span>
        <span class="stat-label">Selesai</span>
    </div>
    <div class="stat-item">
        <span class="stat-number"><?php echo $totalNotes - $doneCount; ?></span>
        <span class="stat-label">Belum Selesai</span>
    </div>
</div>

    <form action="tambah.php" method="POST">
      <input type="text" name="title" placeholder="Judul catatan" required>
      <br>
      <textarea name="content" placeholder="Isi catatan"></textarea>
      <br>
      <button type="submit" class="btn-primary">Tambah Catatan</button>
    </form>

    <hr>

    <form method="GET" action="index.php" onsubmit="return false;">
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
        <button type="button" class="btn-primary" onclick="clearSearch()">Clear</button>
    </div>
    </form>

    <div id="notesList">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
      $doneClass = $row['is_done'] ? "done" : "";
      $checked = $row['is_done'] ? "checked" : "";
    echo "<div class='note-card $doneClass'>";
    echo "<input type='checkbox' $checked onclick=\"toggleNote(" . $row['id'] . ")\">";
    echo "<div class='note-content'>";
    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
    echo "<span class='note-date'>" . date('d M Y, H:i', strtotime($row['created_at'])) . "</span>";
    echo "<div class='note-actions'>";
    echo "<a href='#' class='icon-edit' onclick=\"openEditModal(" . $row['id'] . ", '" . htmlspecialchars(addslashes($row['title'])) . "', '" . htmlspecialchars(addslashes($row['content'])) . "'); return false;\"><i class='fa-solid fa-pen'></i></a>";
    echo "<a href='hapus.php?id=" . $row['id'] . "' class='icon-delete'><i class='fa-solid fa-trash'></i></a>";
    echo "</div></div></div>";
    }
    ?>
    </div>

</div>

<div class="modal-overlay" id="editModalOverlay">
    <div class="modal-box">
        <h3>Edit Catatan</h3>
        <form id="editForm">
            <input type="hidden" id="editNoteId">
            <input type="text" id="editTitle" placeholder="Judul catatan" required>
            <textarea id="editContent" placeholder="Isi catatan"></textarea>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
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
        .then(response => response.json())
        .then(data => {
            document.getElementById('notesList').innerHTML = data.html;
            document.querySelectorAll('.stat-number')[0].textContent = data.total;
            document.querySelectorAll('.stat-number')[1].textContent = data.done;
            document.querySelectorAll('.stat-number')[2].textContent = data.notDone;
        })
        .catch(error => console.error('Error fetching notes:', error));
}

function clearSearch() {
    document.getElementById('keyword').value = '';
    document.getElementById('date').value = '';
    document.getElementById('status').value = '';
    fetchNotes();
}

function toggleNote(id) {
    fetch('toggle.php?id=' + id)
        .then(() => fetchNotes())
        .catch(error => console.error('Error toggling note:', error));
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

function openEditModal(id, title, content) {
    document.getElementById('editNoteId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editContent').value = content;
    document.getElementById('editModalOverlay').classList.add('show');
}

function closeEditModal() {
    document.getElementById('editModalOverlay').classList.remove('show');
}

document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const id = document.getElementById('editNoteId').value;
    const title = document.getElementById('editTitle').value;
    const content = document.getElementById('editContent').value;

    const params = new URLSearchParams({ id, title, content });

    fetch('update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(() => {
        closeEditModal();
        fetchNotes();
    })
    .catch(error => console.error('Error updating note:', error));
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