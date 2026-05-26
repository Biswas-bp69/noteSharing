<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();
checkRole('admin');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id='$id'";

    if ($conn->query($sql)) {
        header("Location: users.php");
    } else {
        echo "❌ Error deleting user!";
    }
}
?>