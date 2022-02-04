<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
unset($_SESSION["LoginErrorMessage"]);
// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// To Do 1 (Practical 2): Validate login credentials with database

//Include the PHP file that establishes database connections handle: $conn
include_once("mysql_conn.php");

//Define the SELECT SQL statement 
$qry = "SELECT Password, Email, Name, ShopperID FROM Shopper WHERE Email = '$email'";
$result = $conn->query($qry); //Execute the SQL and get the returned reuslts
while($row = $result->fetch_array()){
	if (($email == $row['Email']) && ($pwd == $row['Password'])) {
		// Save user's info in session variables
		$_SESSION["ShopperName"] = $row['Name'];
		$_SESSION["ShopperID"] = $row["ShopperID"];
		
		// To Do 2 (Practical 4): Get active shopping cart
		$qry2 = "SELECT sc.ShopCartID, COUNT(sci.ProductID) AS NumItems FROM ShopCart sc LEFT JOIN ShopCartItem sci ON sc.ShopCartID=sci.ShopCartID WHERE sc.ShopperID=$_SESSION[ShopperID] AND sc.OrderPlaced=0";
		$result2 = $conn->query($qry2);
		$row2 = $result2->fetch_array();
		$_SESSION["Cart"] = $row2["ShopCartID"];
		$_SESSION["NumCartItem"] = $row2["NumItems"];
		// Redirect to home page
		header("Location: index.php");
		break;
		// Redirect to home page
	}
	else {
		$_SESSION["LoginErrorMessage"] = "<p class='mb-5' style='color: red;'>Invalid Login Credentials!</p>";
		header("Location: login.php");
	}
}

//Close Database connection
$conn->close();
	
// Include the Page Layout footer
include("footer.php");
?>