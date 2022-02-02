<?php
// Detect the current session
session_start();
// Reading inputs entered in previous page
$pid = $_POST["ProductID"];
$rank = $_POST["Rank"];
$comment = $_POST["Comment"];

//Include the PHP file that establishes database connections handle: $conn
include_once("mysql_conn.php");

//Define the SELECT SQL statement 
$qry = "INSERT INTO Ranking (ShopperID, ProductID, Rank, Comment) VALUES (?,?,?,?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("iiis", $_SESSION["ShopperID"], $pid, $rank, $comment); 	// "iiis" - integer x 3, string
if($stmt->execute()){ 
    $conn->close();
    header('location:productDetails.php?pid=' . $pid);
}//SQL Statement executed successfully
//Close Database connection
