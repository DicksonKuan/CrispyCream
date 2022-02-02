<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style='width:100%; margin:auto;'>
<!-- Display Page Header - Category's name is read 
     from the query string passed from previous page -->
<h1 class="text-center mb-5"><?php echo "$_GET[catName]"; ?></h1>
<style>
    a{
        color:black;
    }
    .card{
        background-color:#f3e2cb;
    }
    a:hover{
        color: #00754e;
        text-decoration: none;
    }
    .btn{
        background-color: #00754e;
        color:white;
    }
    .btn:hover{
        background-color: #ffe0b4;
        color: black;
    }
    .input-group-text{
        background-color: #00754e;
        color:white;
    }
</style>
<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$cid = $_GET["cid"]; //Read category ID from query string
$catName =  $_GET["catName"];
$counter = 1;
//Form SQL retrieve lists of product associated to the Category ID and selects a certain amount
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.Offered, p.OfferedPrice, p.OfferEndDate
		FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
		WHERE cp.CategoryID=?";
$stmt  = $conn->prepare($qry);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result= $stmt->get_result();
$stmt->close();

while($row=$result->fetch_array()){
    // $product = "productDetails.php?pid=$row[ProductID]";
	// $formattedPrice = number_format($row["Price"],2);
    // $img = "./img/products/$row[ProductImage]";

    // //Card
    // echo '<div class="card">';
    // echo "<img src='$img' class='card-img-top'>";
    // echo "<a href='$product' class='col'><h5 class='card-title'>$row[ProductTitle]</h5></a>";
    // echo '<div class="card-footer">';
    // echo "<p class='card-text'>S$ $formattedPrice</p>";
    // echo "</div>";
    // echo '</div>';

    $img = "./img/Products/$row[ProductImage]";
        $productURL=  "productDetails.php?pid=$row[ProductID]";
    
        if($counter == 1){
            echo "<div class='card-group'>";
        }
        echo '<div class="card" style="width: 18rem;">';
        echo "<img class='card-img-top w-50 mx-auto mt-5' src=$img alt='$row[ProductTitle]'>";
        echo '<div class="card-body">';
        echo "<a href='$productURL'><h3 class='card-title'>$row[ProductTitle]</h5></a>";
        if($row["Quantity"]=="0"){
            echo "<p class='card-text' style='font-size: 1.2rem; color: red;'>SOLD OUT</p>";
        }else{
            if($row["Offered"] == 1){
                $formattedPrice = number_format($row["Price"],2);
                $formattedDiscountPrice = number_format($row["OfferedPrice"],2);
                echo "<h4>Price: S$ <del>$formattedPrice</del>
                        <span style='color:red;'> $formattedDiscountPrice</span></h4>";  
                echo "<p style='color:red'>Sales offer ends on: $row[OfferEndDate]</p>";
            }else{
                echo "<p class='card-text' style='font-size: 1.2rem;'>SGD$ $row[Price]</p>";
            }
            echo '<div class="btn-group" role="group" aria-label="Basic example">';
            echo "<a href='$productURL' class='btn'>Add To cart</a>";
            echo "<a href='$productURL' class='btn'>Buy now</a>";
            echo "</div>";
        }
        
        echo "</div></div>";
        if(($counter%3)==0){
            echo "</div>";
            $counter = 1;
        }else{
            $counter += 1;
        }
}
//To fill the left over space with empty cards
if ($counter != 1){ 
    while($counter != 4){
        echo "<div class='card' style='width: 10rem;'><div class='card-body'></div></div>";
        $counter += 1;
    }
    echo "</div>";
}
$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>