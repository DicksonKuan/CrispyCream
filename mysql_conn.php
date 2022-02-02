<?php
//Connection Parameters Dickson
// $servername = 'localhost:3308';
// $username = 'root';
// $userpwd = 'password';
// $dbname = 'crispycream'; 

// //Ethan Connection Parameters 
$servername = 'localhost:3306';
$username = 'root';
$userpwd = 'password';
$dbname = 'crispycream'; 

//Connection Parameters 
// $servername = 'localhost:3306';
// $username = 'root';
// $userpwd = '';
// $dbname = 'crispycream'; 

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);	
}
?>
