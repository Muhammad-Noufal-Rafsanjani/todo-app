<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}

include "includes/koneksi.php";
include "includes/get_notes.php";

$user_id = $_SESSION['user_id'];
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$result = getNotes($conn, $user_id, $keyword, $date, $status);

ob_start();
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
$notesHtml = ob_get_clean();

$totalResult = getNotes($conn, $user_id, '', '', '');
$totalNotes = mysqli_num_rows($totalResult);

$doneResult = getNotes($conn, $user_id, '', '', '1');
$doneCount = mysqli_num_rows($doneResult);

header('Content-Type: application/json');
echo json_encode([
    'html' => $notesHtml,
    'total' => $totalNotes,
    'done' => $doneCount,
    'notDone' => $totalNotes - $doneCount
]);
?>