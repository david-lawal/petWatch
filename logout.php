<?php
session_start();
session_unset();    // Remove all session variables
session_destroy();  // Destroy session
header('Location: index.php'); // Redirect
exit;

?>