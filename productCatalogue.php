<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:100%; margin:auto;">
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

<form action="" method="GET" name="productSearch">
    <div class="input-group mb-3 input-group-lg">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Search donuts: </span>
        </div>
        <input type="search" class="form-control" name="productSearch" id="productSearch" placeholder="Rainbow Bright" aria-label="productSearch" aria-describedby="basic-addon1">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </div>
</form>

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Default get all products
$qry = "SELECT * FROM Product"; 

//Check if search query is empty
if(isset($_GET['productSearch']) && trim($_GET['productSearch']) != ""){
    //Search query
    $keyword = $_GET['productSearch'];
    $qry = "SELECT * FROM product 
            LEFT JOIN catproduct ON product.ProductID = catproduct.ProductID
            LEFT JOIN category ON catproduct.CategoryID = category.CategoryID
            WHERE category.catName LIKE '%$keyword%'
            OR product.ProductTitle LIKE '%$keyword%' 
            OR product.ProductDesc LIKE '%$keyword%'"; 
}

//Execute the SQL and get the result
$result = $conn->query($qry);       
$counter = 1;

//Fill in content into card
//Each row has 3 cards
if($result->num_rows> 0){
    while($row = $result->fetch_array()){
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
        
    };  
    //To fill the left over space with empty cards
    if ($counter != 1){ 
        while($counter != 4){
            echo "<div class='card' style='width: 10rem;'><div class='card-body'></div></div>";
            $counter += 1;
        }
        echo "</div>";
        
    }
}else{
    echo "No records found!";
}
$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
