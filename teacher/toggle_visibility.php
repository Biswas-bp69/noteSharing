<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();
checkRole('teacher');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // current value fetch
    $sql = "SELECT visibility FROM notes WHERE id='$id' AND uploaded_by='{$_SESSION['user_id']}'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {

        $newStatus = ($row['visibility'] == 'public') ? 'private' : 'public';

        $update = "UPDATE notes SET visibility='$newStatus' WHERE id='$id'";
        $conn->query($update);
    }
}

header("Location: my_notes.php");
exit;
?>