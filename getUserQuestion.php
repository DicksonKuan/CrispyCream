<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

// Reading inputs entered in previous page
$email = $_POST["email"];
//Include the PHP file that establishes database connections handle: $conn
include_once("mysql_conn.php");

//Define the SELECT SQL statement 
$qry = "SELECT PwdQuestion FROM Shopper WHERE Email = '$email'";
echo $qry;
$result = $conn->query($qry); //Execute the SQL and get the returned reuslts
if (mysqli_num_rows($result) != 0){
    while($row = $result->fetch_array()){
        // Save user's info in session variables
        $_SESSION["PwdQuestion"] = $row['PwdQuestion'];
        $_SESSION["PwdEmail"] = $email;
        // Redirect to home page
        header("Location: passwordQuestion.php");
        break;
        // Redirect to home page
    }
}
else {
    echo  "<h3 style='color:red'>No user found with entered email address!</h3>";
}

//Close Database connection
$conn->close();
	
// Include the Page Layout footer
include("footer.php");
?>