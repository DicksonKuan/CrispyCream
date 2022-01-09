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
    echo "<p class='card-text'>$row[CatDesc]</p>";
    echo "</div>";

    //Card footer
    $catname= urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    echo "<div class='card-footer'>";
    echo "<a href=$catproduct class='btn btn-primary btn-sm'>Link</a>";
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
