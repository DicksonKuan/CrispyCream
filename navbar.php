<link rel="stylesheet" href="css/site.css">
<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		         <a class='nav-link' href='register.php' style='color:#00754e'>SIGN UP</a>
             </li>
			 <li class='nav-item'>
		        <a class='nav-link' href='login.php' style='color:#00754e'>LOGIN</a>
             </li>";

if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
	$content1 = "welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "
        <li class='nav-item' style='color:#00754e'>
            <a class='nav-link' href='changePassword.php' style='color:#00754e'>Change password</a>
        </li>
        <li class='nav-item' style='color:#00754e'>
            <a class='nav-link' href='logout.php' style='color:#00754e'>Logout</a>
        </li>
    ";


	//To Do 2 (Practical 4) - 
    //Display number of item in cart
	if(isset($_SESSION["NumCartItem"])){
        $content1 .= ",$_SESSION[NumCartItem] item(s) in shopping cart";
    }
}
?>

<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
<link rel="stylesheet" href="css/navbar.css">
<nav class="navbar navbar-expand-md bg-custom navbar-dark">
    <!--Dynamic Text display-->
    
    <span class="navbar-text ml-md-2" style="color: #00754e;">
        <?php echo $content1;?>
    </span>
    
    <!--Toggler/ Collapsible button-->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" .navbar-collapse>
        <span class="toggler-icon top-bar">  </span>
        <span class="toggler-icon middle-bar"> </span>
        <span class="toggler-icon bottom-bar"> </span>

    
    </button>
</nav>

<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
<link rel="stylesheet" href="css/site.css">
<nav class="navbar navbar-expand-md navbar-dark bg-custom center">
    <div class="collapse show navbar-collapse mx-auto " id="collapsibleNavbar">
        <!--Collapsible part of navbar-->
        <ul id="navbarOptions" class="navbar-nav mr-auto nav" >
            <li class="nav-item">
                <a href="category.php" class="nav-link" style="color: #00754e;" >MENU</a>
            </li>
            <li class="nav-item">
                <a href="category.php" class="nav-link" style="color: #00754e;" >PRODUCT</a>
            </li>
            <li class="nav-item">
                <a href="shoppingCart.php" class="nav-link" style="color: #00754e;" >CART</a>
            </li>
        </ul>
        
    </div>
    <div>
        <!--Right-justified menu items-->
        <ul class="navbar-nav ml-auto" style="color: #00754e;">
            <?php echo $content2;?>
        </ul>
    </div>
</nav>
