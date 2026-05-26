<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();
checkRole('admin');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM categories WHERE id='$id'";
    $conn->query($sql);

    header("Location: categories.php");
}
?>