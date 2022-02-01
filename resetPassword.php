<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
// Reading inputs entered in previous page
$email = $_SESSION["PwdEmail"];
$answer = $_POST["pwdquestionanswer"];
echo $answer;
//Include the PHP file that establishes database connections handle: $conn
include_once("mysql_conn.php");

//Define the SELECT SQL statement 
$qry = "SELECT * FROM Shopper WHERE Email = '$email'";
$qry2 = "UPDATE Shopper SET Password = 'newp@55word555' WHERE Email = '$email'";
$result = $conn->query($qry); //Execute the SQL and get the returned reuslts
while($row = $result->fetch_array()){
    if ($answer == $row['PwdAnswer']){
        // Save user's info in session variables
        if ($conn->query($qry2) === TRUE) {
            $_SESSION["SuccessMessage"] = "Your new Password is: newp@55word555, Please change it after Logging In!";
            header("Location: index.php");
            break;
        }
        else{
            $_SESSION["ErrorMessage"] = "Error updating record!";
            header("Location: passwordQuestion.php");
        }
        // Redirect to home page
    }
    else {
        $_SESSION["ErrorMessage"] = "Wrong Answer! Please try again";
    }
}


//Close Database connection
$conn->close();
	
// Include the Page Layout footer
include("footer.php");
?>