<?php
function getNotes($conn, $user_id, $keyword, $date, $status) {
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

    if ($status !== '') {
        $conditions[] = "is_done = ?";
        $types .= "i";
        $params[] = $status;
    }

    $whereClause = implode(" AND ", $conditions);
    $query = "SELECT * FROM notes WHERE $whereClause ORDER BY created_at DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
?>