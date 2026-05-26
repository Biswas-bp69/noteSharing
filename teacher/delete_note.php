<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();
checkRole('teacher');

$user_id = $_SESSION['user_id'];

$id = $_GET['id'];

// DELETE ONLY OWN NOTE
$sql = "DELETE FROM notes WHERE id='$id' AND uploaded_by='$user_id'";

if($conn->query($sql)){
    header("Location: my_notes.php?msg=deleted");
} else {
    echo "Delete failed!";
}
?>