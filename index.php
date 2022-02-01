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

<!-- Category -->
<div class="container mt-5">
<div class="card-group">

<style>
     a{
        color:#00754e;
    }
    .card{
        background-color:#f3e2cb;
    }
    a:hover{
        color: black;
        text-decoration: none;
    }
</style>

<?php
include_once("mysql_conn.php");
$qry = "SELECT * FROM Category ORDER BY CatName ASC";    // Form SQL to select all categories
$result = $conn->query($qry);   

while($row = $result->fetch_array()){
     $img = "./img/category/$row[CatImage]";
     $catname= urlencode($row["CatName"]);
     $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname&page=1";
     echo "<div class='card w-25'>";
     echo "<img src='$img' class='card-img-top mx-auto w-25 mt-5' alt='$row[CatName]'>";
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

