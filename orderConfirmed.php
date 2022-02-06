<?php
session_start();

include("header.php");

if(isset($_SESSION["OrderID"])){

    echo "<link rel='stylesheet' href='css/orderConfirmed.css'";

// this page is displayed after the order has been made already 
	
    // echo "<div class='col-sm-2'>";
    // echo "</div>";
    echo "<div class='col-sm-8'>";

    echo  "<div class='card'>";

	//display the order number and other order details 
    echo "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
    
    echo "<p>Your order will be sent to $_SESSION[Address]</p>";
    echo "<p>The delivery day will be $_SESSION[deliverDate]";
    echo "<p>The delivery will have the message </br> '$_SESSION[msg]' ";
    
	
    if ($_SESSION['radioVal'] == "express" ){
        echo "<p> Your delivery will be done through express shipping </p>";
        echo "<p> Order will arrive at $_SESSION[deliveryTime]</p>";
	}
	else if ($_SESSION['radioVal'] == "normal" ){
        echo "<p> Your delivery will be done through normal shipping </p>";
        echo "<p> Order will arrive in a day</p>";
	}


    echo "<p>Thank you for your purchase.&nbsp;&nbsp;";
    echo "<a href='index.php'>Continue shopping</a></p>";



    echo "</div>";

    // echo "<div class='col-sm-2'>";
    // echo "</div>";

}

include("footer.php");


?>
