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
<img src="img/brandimage.png" class="img-fluid" 
     style="display:block; margin:auto;"/>

<?php 
// Include the Page Layout footer
include("footer.php"); 
?>

