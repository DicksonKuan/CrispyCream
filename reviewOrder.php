<!-- This page is for the user to review their order which includes price etc etc before buying their stuff-->

<script type=text/javascript>



</script>


<?php
session_start();
include("header.php");
include_once("myPayPal.php");
include("mysql_conn.php");
// include("reviewOrderfunctions.php");

//check if the user is logged in and redirect them if they are not

//for testing
$_SESSION["ShopperID"] = 1;
$_SESSION["Cart"] = 1;


$deliveryOption = "";
function checkdelivery($src){
    echo '<script>alert("Welcome to Geeks for Geeks")</script>';
}

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
    

    $qry = "SELECT s.*, (p.Price * s.Quantity) AS Total, p.ProductImage, p.ProductDesc
            FROM shopcartitem s
            INNER JOIN Product p ON s.ProductID = p.ProductID
            WHERE ShopCartID = ?";
    
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

            echo "<img src='img/products/$row[ProductImage]' alt='$row[Name]'>";
            echo "</div>"; //left div for name and desc end

            echo "<div class='col-sm-4'>";//middle div for name and desc start
            echo "<h4 style='text-align:center'><b>$row[Name]</b></h4>";
            echo "<h5 style='text-align:center'>$row[ProductDesc]</h5>";
            echo "</div>"; //middle div for name and desc end

            echo "<div class='col-sm-4'>";//right div for name and desc start
            //echo "<select style='text-align:center; padding-top:10px' name='Quantity' onChange='this.form.submit()'>";
            echo "<p id=orderedQuantity >You ordered $row[Quantity] of these!</p>";
        //     for ($i = 1; $i <= 100; $i++){
        //         if ($i == $row["Quantity"]){
        //             $selected = "selected";
        //         }
        //         else{
        //             $selected = "";
        //         }
                
        //     }
        //    // echo "</select>";

            echo "<form action='cartFunctions.php' method='post'>";
            echo "<input type='hidden' name='action' value='remove' />";
            echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
            echo "<input id='imginput' type='image' src='img/red-cross.png' title='Remove Item' />";
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
echo "<div class='card' style='height: auto;>";
echo "<div class='container' style='100%'>";
//location
//delivery choice

echo "<form method='post' action='checkoutProcess.php'>";
echo "<h1>Order Summary </h1>";

$finalQuantity = 0;
$finalPrice = 0;
$qry = "SELECT s.ProductID, s.Quantity, p.Price, (p.Price * s.Quantity) AS total
            FROM shopcartitem s
            INNER JOIN Product p ON s.ProductID = p.ProductID
            WHERE ShopCartID = ?";

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

        $finalPrice += $row["total"];
        $finalQuantity += $row["Quantity"];

    };
};



echo "<h2>Subtotal ($finalQuantity Items)  $$finalPrice </h2>";
echo "<p>Shipping and tax calculated at checkout</p>";

echo "<label for='name'>Name</label>";
echo "<input type='text' id='fname' name='fname' required><br>";



echo "<br />";

echo "<label for='deliveryRadio'>Express</label>	";
echo "<input type='radio' name='deliveryRadio' id='deliveryRadio' value='express'  />";
echo "<label for='deliveryRadio'>normal</label>";
echo "<input type='radio' name='deliveryRadio' id='deliveryRadio' value='normal' checked/>";

echo "<br />";

echo "<label for='phoneNo'>Phone Number</label>";
echo "<input type='tel' name='phoneNo' id='phoneNo' required placeholder='91234567' pattern='[8-9][0-9]{7}'/>";
echo "<br />";
echo "<label for='address'>Address</label>";
echo "<input type='text' name='address' id='address' required />";

echo "<br />";
echo "<label for='email'>Email</label>";
echo "<input type='text' name='email' id='email' required />";

echo "<br />";
echo "<label for='msg'>Send a message!</label>";
echo "<br />";

echo "<textarea id='msg' name='msg' rows='4' cols='30' placeholder='send a message here!'>  </textarea>";



echo "<br />";

echo "<input type='image' style='float:right;'
     src = 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
echo "</form>";

        

echo "</div>";
echo "</div>";
echo "</div>"; //this closes the right side of the items summary panel

echo "</div>";




include ("footer.php");

?>




