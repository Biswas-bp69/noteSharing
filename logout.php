<?php

session_start();

// सबै session data हटाउने
session_unset();

// session destroy गर्ने
session_destroy();

// login page मा पठाउने
header("Location:index.php");
exit();

?>