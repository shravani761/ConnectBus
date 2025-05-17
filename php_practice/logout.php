<?php
session_start();
session_unset();
session_destroy();
header("Location: userlogin.php"); // Redirect to login page
exit();
?>
