<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:100%; margin:auto;">
<!-- Display Page Header -->
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
    //Card carousel - to display top 3 offered item
    $qry2 = "SELECT * FROM product 
            LEFT JOIN catproduct 
            ON product.ProductID = catproduct.ProductID 
            WHERE catproduct.CategoryID = $row[CategoryID]";   
    $result2 = $conn->query($qry2);       //Execute the SQL and get the result
    $counter = 1;
    while($row2 = $result2->fetch_array()){
        $img = "./img/Products/$row2[ProductImage]";
        echo '<div class="card bg-dark text-white">';
        echo "<img src='$img' class='card-img' alt='$row2[ProductTitle]''>";
        echo '<div class="card-img-overlay">';
        echo "<h5 class='card-title'>$row2[ProductTitle]</h5>";
        echo "<p class='card-text'>$row2[Price]</p>";
        echo "<p class='card-text'>$row2[ProductDesc]</p>";
        echo "</div></div>";
        $counter += 1;
    };

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
