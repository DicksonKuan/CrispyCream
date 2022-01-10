<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

<?php 
$pid=$_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while($row = $result->fetch_array()){
    
    $img = "./img/Products/$row[ProductImage]";

    echo "<div class='row'>";

    //Left side
    echo "<div class='col-5'>";
    echo "<p><img src='$img' class='w-100'/></p>";
    echo "</div>";

    //Right side
    echo "<div class='col'>";
    echo "<h2>$row[ProductTitle]</h2>";
    //To check if there is stock, else display out of stock
    if($row["Quantity"] != 0){
        $formattedPrice = number_format($row["Price"],2);
        $formattedDiscountPrice = number_format($row["OfferedPrice"],2);

        //Price
        if($row["Offered"] == 1){
            echo "<h4>Price: S$ <del>$formattedPrice</del>
            <span style='font-weight:bold; color:red;'> $formattedDiscountPrice</span></h4>";  
            echo "<p style='color:red'>Sales offer ends on: $row[OfferEndDate]</p>";
        }else{
            echo "<h4>Price:<span style='font-weight:bold; color:red;'>
            S$ $formattedPrice</span></h4>";  

        }

        //Add to cart form
        echo "<form action='cartFunctions.php' method='POST'>";
        echo "<div class='row mx-1'>";

        //Request Quantity
        echo '<div class="input-group flex-nowrap w-25 ">';
        echo '<div class="input-group-prepend">';
        echo '<span class="input-group-text" id="addon-wrapping">Quantity</span>';
        echo '</div>';
        echo "<input type='number' required class='form-control' style='appearance: textfield;' value=1 min='1' max='10' required aria-describedby='addon-wrapping'>";
        echo "</div>";
        echo "</div>";

        echo "<div class='btn-grp mt-5' role='group'>";
        //Add to cart 
        echo "<button type='submit' class='btn btn-light'>Add to cart</button>";
        //Buy now
        echo "<button type='submit' class='btn btn-light'>Buy now</button>";

        echo "</div>";
        echo "</form>";
    }else{
        echo "<h4><span style='font-weight:bold; color:red;'>SOLD OUT</span></h4>";  
    }

    //Product description
    echo "<div class='mt-5'>";
    echo "<h5>$row[ProductDesc]</h5>";
    $qry ="SELECT s.SpecName, ps.SpecVal FROM productspec ps 
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID = ? 
            ORDER BY ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    while($row2 = $result2->fetch_array()){
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br>";
    }

    echo "</div>";
    echo "</div>";
    echo "</div>";

   
}

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>

