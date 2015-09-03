<?php 
session_start();
session_unset();
header("Location: signup.php");
exit;
?>	