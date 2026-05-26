<?php
session_start();

// login check
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit;
    }
}

// role check
function checkRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $role) {
        echo "❌ Access Denied!";
        exit;
    }
}
?>