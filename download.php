<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['file']) && isset($_GET['id'])) {

    $file = $_GET['file'];
    $note_id = $_GET['id'];

    $path = "uploads/" . $file;

    if (file_exists($path)) {

        // track download
        $check = $conn->query("SELECT * FROM downloads WHERE user_id='$user_id' AND note_id='$note_id'");

        if ($check->num_rows == 0) {
            $conn->query("INSERT INTO downloads (user_id, note_id) VALUES ('$user_id', '$note_id')");
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

        readfile($path);
        exit;

    } else {
        echo "File not found!";
    }
}
?>