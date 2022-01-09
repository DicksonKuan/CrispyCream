<?php
// Detect the current session
session_start();

//Check if there is valid session
if (isset($_SESSION["ShopperName"])) {
    //Destroy session
	session_destroy();
}

// Redirect to home page
header("Location: login.php");
?>