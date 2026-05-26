<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();
checkRole('admin');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // पहिले file delete गर्ने (optional improvement)
    $getFile = $conn->query("SELECT file FROM notes WHERE id='$id'");
    $fileData = $getFile->fetch_assoc();

    if ($fileData) {
        $filePath = "../uploads/" . $fileData['file'];
        if (file_exists($filePath)) {
            unlink($filePath); // delete file
        }
    }

    // database delete
    $sql = "DELETE FROM notes WHERE id='$id'";

    if ($conn->query($sql)) {
        header("Location: notes.php");
    } else {
        echo "❌ Error deleting note!";
    }
}
?>