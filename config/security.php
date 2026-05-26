<?php

// 🧹 clean input function
function clean($data) {
    return htmlspecialchars(trim($data));
}

// 📁 allowed file types
function allowedFile($fileName) {
    $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    return in_array($ext, $allowed);
}

?>