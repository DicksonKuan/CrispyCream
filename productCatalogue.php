<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:100%; margin:auto;">
<!-- <style>
    .card:hover{
        border: 3px dotted #ff7a59;
    }
</style> -->
<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
$qry = "SELECT * FROM Product";    // Form SQL to select all categories
$result = $conn->query($qry);       //Execute the SQL and get the result
$counter = 1;

//Fill in content into card
//Each row has 3 cards
while($row = $result->fetch_array()){
    $img = "./img/Products/$row[ProductImage]";
    $productURL=  "productDetails.php?pid=$row[ProductID]";

    if($counter == 1){
        echo "<div class='card-group'>";
    }
    echo '<div class="card" style="width: 18rem;">';
    echo "<img class='card-img-top' src=$img alt='$row[ProductTitle]'>";
    echo '<div class="card-body">';
    echo "<h3 class='card-title'>$row[ProductTitle]</h5>";
    if($row["Quantity"]=="0"){
        echo "<p class='card-text' style='font-size: 1.2rem; color: red;'>SOLD OUT</p>";
    }else{
        echo "<p class='card-text' style='font-size: 1.2rem;'>SGD$ $row[Price]</p>";
    }
    echo "<a href='$productURL' class='btn btn-primary'>View more</a>";
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
$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>
