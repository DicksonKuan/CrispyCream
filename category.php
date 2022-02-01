<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:100%; margin:auto;">
<!-- Display Page Header -->
<div class="row" style="padding:5px"> <!-- Start of header row -->
    <div class="col-12">
        <span class="page-title">Products category</span>
        <p>Select a category listed below:</p>
    </div>
</div> <!-- End of header row -->
<style>
    
</style>
<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
$qry = "SELECT * FROM Category ORDER BY CatName ASC";    // Form SQL to select all categories
$result = $conn->query($qry);       //Execute the SQL and get the result

//Card row 
echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
//Display each category in a row 
while($row = $result->fetch_array()){

    //Card col
    echo "<div class='col'>";
    echo "<div class='card h-100'>";
    
    //Card header
    $img = "./img/category/$row[CatImage]";
    echo "<h5 class='card-header'>
            <img src='$img' class='w-25' alt='$row[CatName]'>
        </h5>";

    //Card body
    echo "<div class='card-body'>";
    echo "<h5>TOP SELLERS</h5>";
    //Card carousel - to display top 3 offered item
    $qry = "SELECT ProductImage FROM product 
            LEFT JOIN catproduct 
            ON product.ProductID = catproduct.ProductID 
            WHERE catproduct.CategoryID = $row[CategoryID]
            ORDER BY  product.offered DESC
            LIMIT 3";   
    $result2 = $conn->query($qry);       //Execute the SQL and get the result
    echo "<div id='$row[CatName]' class='carousel slide' data-ride='carousel'>";
    echo "<ol class='carousel-indicators'>";
    echo "<li data-target='#$row[CatName]' data-slide-to='0' class='active'></li>";
    echo "<li data-target='#$row[CatName]' data-slide-to='1'></li>";
    echo "<li data-target='#$row[CatName]' data-slide-to='2'></li>";
    echo "</ol>";
    echo '<div class="carousel-inner">';
    $counter = 1;
    while($row2 = $result2->fetch_array()){
        $img = "./img/Products/$row2[ProductImage]";
        if($counter == 1){
            echo "<div class='carousel-item active'> <img src='$img' class='d-block w-25' alt='...''></div>";
        }else{
            echo "<div class='carousel-item'> <img src='$img' class='d-block w-25' alt='...''></div>";
        }
        $counter +=1;
    };

    echo "</div>";
    echo "</div>";    

    echo "<p class='card-text'>$row[CatDesc]</p>";
    echo "</div>";

    //Card footer
    $catname= urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname&page=1";
    echo "<div class='card-footer'>";
    echo "<a href=$catproduct class='btn btn-primary btn-sm'>View more</a>";
    echo "</div>";

    //End of card
    echo "</div>";
    echo "</div>";
};  
echo "</div>";

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
