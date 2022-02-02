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
</style>
<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
?>
<link rel="stylesheet" href="css/site.css">
<h5 style="color:#00754e; text-align:center">
     <?php 
          if (isset($_SESSION["SuccessMessage"])) {
               echo $_SESSION["SuccessMessage"];
          }
     ?>
</h5>
<img src="img/brandimage.png" class="img-fluid w-25" style="display:block; margin:auto;"/>
<div class="container mt-5">

<div class='card'>
     <div class='card-header'>
     <h1 class="text-center"><b>Promotional Item</b></h1>
     </div>
     <div class='card-body'>
          <div class='card-group'>
               <?php
                    include_once("mysql_conn.php");
                    //Display promotional items in card group
                    $qryPromotion = "SELECT * FROM product WHERE Offered = 1";
                    $resultPromotion = $conn->query($qryPromotion);   
                    $counter = 1;
                    while($row = $resultPromotion->fetch_array()){
                         $img = "./img/Products/$row[ProductImage]";
                         $productURL=  "productDetails.php?pid=$row[ProductID]";
                         if($counter <= 5){
                              echo "<div class='card'>";
                              echo "<img class='card-img-top w-50 mx-auto mt-5' src=$img alt='$row[ProductTitle]'>";
                              echo '<div class="card-body">';
                              echo "<a href='$productURL'><h5 class='card-title'>$row[ProductTitle]</h5></a>";
                              $formattedPrice = number_format($row["Price"],2);
                              $formattedDiscountPrice = number_format($row["OfferedPrice"],2);
                              echo "<h4>Price: S$ <del>$formattedPrice</del>
                                      <span style='color:red;'> $formattedDiscountPrice</span></h4>";  
                              echo "<p style='color:red'>Sales offer ends on: $row[OfferEndDate]</p>";
                              echo '<div class="btn-group" role="group" aria-label="Basic example">';
                              echo "<a href='$productURL' class='btn'>Add To cart</a>";
                              echo "<a href='$productURL' class='btn'>Buy now</a>";
                              echo "</div>";
                              echo "</div></div>";
                         }
                    }
               ?>
          </div>
     </div>
</div>

<!-- Category -->
<div class="card-group">
<?php


// Display all categories
$qry = "SELECT * FROM Category ORDER BY CatName ASC";  
$result = $conn->query($qry);   

while($row = $result->fetch_array()){
     $img = "./img/category/$row[CatImage]";
     $catname= urlencode($row["CatName"]);
     $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
     echo "<div class='card w-25'>";
     echo "<img src='$img' class='card-img-top mx-auto w-50 mt-5' alt='$row[CatName]'>";
     echo "<div class='card-body mt-3'>";
     echo "<a href='$catproduct'><h5 class='card-title text-center'>$row[CatName]</h5></a>";
     echo "<p class='card-text text-center'>$row[CatDesc]</p>";
     echo "</div></div>";
}

?>
</div>
</div>

<?php 
// Include the Page Layout footer
include("footer.php"); 
?>

