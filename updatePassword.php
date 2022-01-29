<?php 
    // Include the page layout header
    include("header.php");
    session_start();// Detect the current session
    if (isset($_SESSION["SuccessMessage"])) {
        unset($_SESSION["SuccessMessage"]);
    }
    if (isset($_SESSION["ErrorMessage"])) {
        unset($_SESSION["ErrorMessage"]);
    }
    $password = $_POST["newpassword"];
    $shopperid = $_SESSION["ShopperID"];
    
    include_once("mysql_conn.php");
    $qry = "UPDATE Shopper SET Password = '$password' WHERE ShopperID = $shopperid";
    if ($conn->query($qry) === TRUE) {
        $_SESSION["SuccessMessage"] = "Password successfully updated!";
        header("Location: index.php");
    }
    else{
        $_SESSION["ErrorMessage"] =  "Error updating record!" ;
        header("Location: updatePassword.php");
    }    
    //Display Page layout header with updated session state and links
    include("header.php");
    include("footer.php");
?>