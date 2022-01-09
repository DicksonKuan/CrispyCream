<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style='width:100%; margin:auto;'>
<!-- Display Page Header - Category's name is read 
     from the query string passed from previous page -->
<div class="row" style="padding:5px">
	<div class="col-12">
		<span class="page-title"><?php echo "$_GET[catName]"; ?></span>
	</div>
</div>

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$cid = $_GET["cid"]; //Read category ID from query string
$pageNo = $_GET["page"];
$catName =  $_GET["catName"];
//Form SQL retrieve lists of product associated to the Category ID and selects a certain amount
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity 
		FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
		WHERE cp.CategoryID=?
        LIMIT 3 OFFSET ?";
$stmt  = $conn->prepare($qry);
$page = $pageNo-1;
$stmt->bind_param("ii", $cid, $page);
$stmt->execute();
$result= $stmt->get_result();
$stmt->close();

echo '<div class="card-group w-100 row">';
while($row=$result->fetch_array()){
    $product = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row["Price"],2);
    $img = "./img/products/$row[ProductImage]";

    //Card
    echo "<a href='$product' class='col'>";
    echo '<div class="card">';
    echo "<img src='$img' class='card-img-top'>";
    echo "<h5 class='card-title'>$row[ProductTitle]</h5>";
    echo '<div class="card-footer">';
    echo "<p class='card-text'>S$ $formattedPrice</p>";
    echo "</div>";
    echo '</div>';
    echo '</a>';
}
echo '</div>';

//Pagination
echo '<nav aria-label="...">';
echo '<ul class="pagination">';
if($pageNo == 1){ //Disable previous button if its first page
    echo '<li class="page-item disabled">';
}else{
    echo '<li class="page-item">';
    $pageNo = $pageNo -=1;
}
$catProductURL= "catProduct.php?cid=$cid&catName=$catName&page=$pageNo";
echo "<a class='page-link' href=$catProductURL>Previous</a>";
echo "</li>";

for ($x=1;$x<=3;$x++){
    if($pageNo == $x){
        echo "<li class='page-item active'><a class='page-link' href='catProduct.php?cid=$cid&catName=$catName&page=$x'>1</a></li>";
    }
    echo "<li class='page-item'><a class='page-link' href='catProduct.php?cid=$cid&catName=$catName&page=$x'>$x</a></li>";
}

if($pageNo == 3){ //Disable next button if its first page
    echo '<li class="page-item disabled">';
}else{
    echo '<li class="page-item">';
    $pageNo = $pageNo +=2;
}
$catProductURL= "catProduct.php?cid=$cid&catName=$catName&page=$pageNo";
echo "<a class='page-link' href=$catProductURL>Next</a>";
echo "</li>";

echo "</ul>";
echo "</nav>";

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>