<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

<?php 
$pid=$_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

while($row = $result->fetch_array()){
    
    $img = './img/Products/';
    $img .= $row["ProductImage"];
    echo "<div class='row'>";

    //Left side
    echo "<div class='col-5'>";
    echo "<p><img src='$img' class='w-100'/></p>";
    echo "</div>";

    //Right side
    echo "<div class='col'>";
    echo "<h2>$row[ProductTitle]</h2>";
    //To check if there is stock, else display out of stock
    if($row["Quantity"] != 0){
        $formattedPrice = number_format($row["Price"],2);

        //Price
        if($row["Offered"] == 1){
            $formattedDiscountPrice = number_format($row["OfferedPrice"],2);
            echo "<h4>Price: S$ <del>$formattedPrice</del>
            <span style='font-weight:bold; color:red;'> $formattedDiscountPrice</span></h4>";  
            echo "<p style='color:red'>Sales offer ends on: $row[OfferEndDate]</p>";
        }else{
            echo "<h4>Price:<span style='font-weight:bold; color:red;'>
            S$ $formattedPrice</span></h4>";  

        }

        //Add to cart form
        echo "<form action='cartFunctions.php' method='POST'>";
        echo "<div class='row mx-1'>";

        //Request Quantity
        echo '<div class="input-group flex-nowrap w-25 ">';
        echo '<div class="input-group-prepend">';
        echo '<span class="input-group-text" id="addon-wrapping">Quantity</span>';
        echo '</div>';
        echo "<input type='number' required class='form-control' style='appearance: textfield;' value=1 min='1' max='10' required aria-describedby='addon-wrapping'>";
        echo "</div>";
        echo "</div>";

        echo "<div class='btn-grp mt-5' role='group'>";
        //Add to cart 
        echo "<button type='submit' class='btn btn-light'>Add to cart</button>";
        //Buy now
        echo "<button type='submit' class='btn btn-light'>Buy now</button>";

        echo "</div>";
        echo "</form>";
    }else{
        echo "<h4><span style='font-weight:bold; color:red;'>SOLD OUT</span></h4>";  
    }

    //Product description
    echo "<div class='mt-5'>";
    echo "<h5>$row[ProductDesc]</h5>";
    $qry ="SELECT s.SpecName, ps.SpecVal FROM productspec ps 
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID = ? 
            ORDER BY ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    while($row2 = $result2->fetch_array()){
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br>";
    }
    $qry= "SELECT ROUND(AVG(Rank), 1) AS Average FROM Ranking WHERE ProductID = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $avgresult = $stmt->get_result();
    $stmt->close();
    if (mysqli_num_rows($avgresult) != 0) {
        while($row = $avgresult->fetch_array()){
            $one_decimal_place = number_format(2.10, 1);
            echo("Average rating score is: $row[Average]");
        }
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
if (isset($_SESSION["ShopperID"])) {
    $qry = "SELECT * from ranking where ProductID=? AND ShopperID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $pid, $_SESSION["ShopperID"]); 	// "i" - integer 
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if (mysqli_num_rows($result) == 0) { 
        $ratingInputHTML = 
        '<div class="row m-2 shadow rounded" style=" text-align: center; background-color:#EBEDEE;">
        <div class="col m-4">
        <h5 style="font-weight:bold;">Give this doughnut a rating!</h5>
        <form action="giveRating.php" method="POST">
        <div class="form-group" style="display: none;">';
        $ratingInputHTML .=  "<input class='form-check-input' type='number' name='ProductID' id='ProductID' value=$pid readonly>";
        $ratingInputHTML .= '</div>
        <div class="form-group">
        <label for="RatingScore">Give a rating out of 5</label><br>
        <div class="form-check form-check-inline">
            <span class="mr-3">Bad</span>
            <input class="form-check-input" type="radio" name="Rank" id="RatingScore1" value="1">
            <label class="form-check-label" for="RatingScore1">
               1
            </label> 
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="Rank" id="RatingScore2" value="2">
            <label class="form-check-label" for="RatingScore2">
                2
            </label>
        </div>
        <div class="form-check disabled form-check-inline">
        <input class="form-check-input" type="radio" name="Rank" id="RatingScore3" value="option3">
        <label class="form-check-label" for="RatingScore3">
            3
        </label>
        </div>
        <div class="form-check disabled form-check-inline">
            <input class="form-check-input" type="radio" name="Rank" id="RatingScore4" value="4">
            <label class="form-check-label" for="RatingScore4">
                4
            </label>
        </div>
        <div class="form-check disabled form-check-inline">
            <input class="form-check-input" type="radio" name="Rank" id="RatingScore5" value="5">
            <label class="form-check-label" for="RatingScore5">
                5
            </label>
            <span class="ml-3">Good</span>
        </div>
        </div>
        <div class="form-group">
            <label for="Comment">Comment</label> 
            <textarea class="form-control" name="Comment" id="Comment" placeholder="Enter Your Comment"></textarea>
        </div>
        <button type="submit" class="btn btn-lg btn-block" type="button" style="background-color:#00754e; color:white;">Submit</button>
        </form>
        </div>
        </div>';
        echo ($ratingInputHTML);
    } else { 
        while($row = $result->fetch_array()){
            $userrating = "";
            for ($x = 0; $x < $row["Rank"]; $x++) {
                $userrating .= '<i class="fa fa-star" style="font-size:50px; color: #FFDB58;"></i>';
            }
            for ($y = 0; $y < (5 - $row["Rank"]); $y++) {
                $userrating .= '<i class="fa fa-star-o" style="font-size:50px"></i>';
            }
            echo "<div class='row'>";
                echo "<div class='col-12 p-2 pt-4' style='background-color: white; text-align: center;'>";
                    echo ("<h3> Your Rating: </h3>");
                    echo ($userrating);
                    echo ("<h4>");
                    echo($row["Comment"]);
                    echo("</h4>");
                echo "</div>";
            echo "</div>";
        }
     }  
}

$qry = "SELECT * from ranking where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if (mysqli_num_rows($result) == 0) { 
    echo ("<div class='row m-2 p-4 shadow rounded' style='background-color: #EBEDEE;'>
    <div class='col-12' style='text-align:center;'>
        <h3 style='font-weight:bold;'>No Ratings Yet!</h3>
    </div>
</div>");
}
else{
    echo ("<div class='row pt-5' style='background-color: white;'>
            <div class='col-12' style='text-align:center;'>
                <h3>All Ratings</h3>
            </div>
        </div>");
    echo ("<div class='row' style='height:15vh; overflow-y:auto;'>");
    $qry2 = "SELECT * from Shopper where ShopperID=?";        
    $doughnutRatings = "";
    while($row = $result->fetch_array()){
        $ratingscore = $row["Rank"];
        //loop through all ratings for current doughnut
        $stmt2 = $conn->prepare($qry2);
        $stmt2->bind_param("i", $row["ShopperID"]);// "i" - integer 
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $stmt2->close();
        while($row2 = $result2->fetch_array()){
            //display rating and comment with user's name
            $doughnutRatings .= "<div class='col-lg-12 p-2 pt-4' style='text-align:center; background-color: white;'>
                                    <h5>";
            $doughnutRatings .= $row2["Name"];
            $doughnutRatings .= "</h5>";
            for ($x = 0; $x < $row["Rank"]; $x++) {
                $doughnutRatings .= '<i class="fa fa-star" style="font-size:30px; color: #FFDB58;"></i>';
            }
            for ($y = 0; $y < (5 - $row["Rank"]); $y++) {
                $doughnutRatings .= '<i class="fa fa-star-o" style="font-size:30px"></i>';
            }
            $doughnutRatings .= "<h5>";
            $doughnutRatings .= $row["Comment"];
            $doughnutRatings .= "</h5>";
            $doughnutRatings .= "</div>";
        }
    }
    echo($doughnutRatings);
    echo "</div>";
}
$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>

