<!-- This page is for the user to review their order which includes price etc etc before buying their stuff-->

<?php
session_start();
include("header.php");
include_once("myPayPal.php");
include("mysql_conn.php");

//check if the user is logged in and redirect them if they are not

//for testing
$_SESSION["ShopperID"] = 1;
$_SESSION["Cart"] = 1;



if(! isset($_SESSION["ShopperID"])){
    header ("Location: login.php");
    exit;
}
echo "<link rel='stylesheet' href='css/reviewOrder.css'";
echo "<h1> Please check the items in your cart first </h1>";

echo "<div class='row'>";

echo "<div id='cartItems' class='col-sm-8'> ";
if (isset($_SESSION["Cart"])){ //if a cart variable does exist in the session 
    //it should be image -> name v desc -> amount + remove from cart
    

    $qry = "SELECT *, (Price * Quantity) AS Total
            FROM shopcartitem WHERE ShopCartID = ?";
    
    $stmt = $conn->prepare($qry);
    $stmt -> bind_param("i", $_SESSION["Cart"]);
    $stmt -> execute();
    $result = $stmt->get_result();
    $stmt->close();
    //gets all the shop cart items into a $result value

    $_SESSION["Items"] = array();
    $subTotal = 0; 

    if ($result->num_rows > 0){ //if there are any records at all from that shop cart ID
        //create the small containers with all the informaiton 
        //probably put headers here later

        
        while ($row = $result->fetch_array()){
            
            echo "<div class='row'>";
            echo "<div class='card'>";
            echo "<div class='container'>";

            echo "<div class='row'>";

            echo "<div class='col-sm-4'>";//left div for name and desc start

            echo "<h5 style='text-align:center'>Image</h5>";
            echo "</div>"; //left div for name and desc end

            echo "<div class='col-sm-4'>";//middle div for name and desc start
            echo "<h4 style='text-align:center'><b>$row[Name]</b></h4>";
            echo "<h5 style='text-align:center'>desc</h5>";
            echo "</div>"; //middle div for name and desc end

            echo "<div class='col-sm-4'>";//right div for name and desc start
            //echo "<select style='text-align:center; padding-top:10px' name='Quantity' onChange='this.form.submit()'>";
            echo "<p>you ordered $row[Quantity] of these!</p>";
            for ($i = 1; $i <= 100; $i++){
                if ($i == $row["Quantity"]){
                    $selected = "selected";
                }
                else{
                    $selected = "";
                }
                
            }
           // echo "</select>";

            echo "<form action='cartFunctions.php' method='post'>";
            echo "<input type='hidden' name='action' value='remove' />";
            echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
            echo "<input type='image' src='images/trash-can.png' title='Remove Item' style='color:red; padding-top:20px'/>";
            echo "</form>";

          
            echo "</div>"; //right div for name and desc end

            echo "</div>";

            


            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br />";
           
            $_SESSION["Items"][] = array("productId"=>$row["ProductID"], "name"=>$row["Name"], "price"=>$row["Price"], "quantity"=>$row["Quantity"]);

            $subTotal += $row["Total"];
            $_SESSION["SubTotal"] = round($subTotal, 2);
        }
    }
    else{
        echo "<h3 style='text-align:center; color:#ff387e;'> Your shopping cart is empty!</h3>";
    }
    //$conn->close();
}
else{
    echo "<h3 style='text-align:center; color:#ff387e;'> Your shopping cart is empty!</h3>";
}


echo "</div>";//this is for the left side items list

echo "<div id='cartSummary' class='col-sm-4'>";
echo "<div class='card' style='height:500px>";
echo "<div class='container' style='100%'>";
//location
//delivery choice

echo "<form method='post' action='checkoutProcess.php'>";
echo "<input type='image' style='float:right;'
     src = 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
echo "</form>";
        

echo "</div>";
echo "</div>";
echo "</div>"; //this closes the right side of the items summary panel

echo "</div>";




include ("footer.php");

?>