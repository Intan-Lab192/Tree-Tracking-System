<?php

session_start();

/* Check if user has logged in */

if(!isset($_SESSION['username']))
{
    header("Location: ../authentication/login.php");
    exit;
}

/* Prevent browser caching */

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

?>