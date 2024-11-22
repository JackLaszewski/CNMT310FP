<?php
session_start();


$_SESSION = array();

//Destroying the session 
session_destroy();

//Return back to the main page
header("Location: index.php");
exit;
?>