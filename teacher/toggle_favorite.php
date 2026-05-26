<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();

$user_id = $_SESSION['user_id'];
$note_id = $_GET['id'];

// check exists
$check = $conn->query("SELECT * FROM favorites WHERE user_id='$user_id' AND note_id='$note_id'");

if ($check->num_rows == 0) {
    $conn->query("INSERT INTO favorites (user_id, note_id) VALUES ('$user_id', '$note_id')");
} else {
    $conn->query("DELETE FROM favorites WHERE user_id='$user_id' AND note_id='$note_id'");
}

header("Location: favorites.php");
exit;
?>