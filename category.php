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
</style>
<div class="card-group">
<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
$qry = "SELECT * FROM Category ORDER BY CatName ASC";    // Form SQL to select all categories
$result = $conn->query($qry);       //Execute the SQL and get the result
$counter = 1;
while($row = $result->fetch_array()){
    $img = "./img/category/$row[CatImage]";
    $catname= urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    $qryProduct = "SELECT ProductImage FROM product 
            LEFT JOIN catproduct 
            ON product.ProductID = catproduct.ProductID 
            WHERE catproduct.CategoryID = $row[CategoryID]
            ORDER BY  product.offered DESC
            LIMIT 3";  
    $resultProduct = $conn->query($qryProduct);

    echo "<div class='card'>";
    echo "<img src='$img' class='card-img-top mx-auto w-25 mt-5' alt='$row[CatName]'>";
    echo "<div class='card-head mt-5'>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='row'>";
    //Display 3 donuts
    while($rowProduct = $resultProduct->fetch_array()){
        $productImg = "./img/products/$rowProduct[ProductImage]";
        echo "<div class='col'>";
        echo "<img src='$productImg' class='card-img-top mx-auto w-75'>";
        echo "</div>";
    }
    echo "</div>";
    echo "<a href='$catproduct'><h5 class='card-title text-center mt-5'>$row[CatName]</h5></a>";
    echo "<p class='card-text text-center'>$row[CatDesc]</p>";
    echo "</div></div>";
    $counter += 1;
}

?>
</div>
<?php 
$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>