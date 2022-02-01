<?php 
    // Include the page layout header
    include("header.php");
    if (isset($_SESSION["SuccessMessage"])) {
        unset($_SESSION["SuccessMessage"]);
    }
    if (isset($_SESSION["ErrorMessage"])) {
        unset($_SESSION["ErrorMessage"]);
    }
    $name = $_POST["fullname"];
    $birthDate = $_POST["birthdate"];
    $formattedBirthDate = date("Y-m-d", strtotime($birthDate));
    $address = $_POST["address"];
    $country = $_POST["country"];
    $phone = $_POST["phonenumber"];
    $email = $_POST["emailaddress"];
    $activeStatus = $_POST["activestatus"];
    session_start();// Detect the current session
    include_once("mysql_conn.php");
    function checkPhoneNumberExists($phonenum, $emailaddr, $conn){
        $qry = "SELECT * FROM Shopper WHERE Phone = '$phonenum' AND Email != '$emailaddr'";
        $result = $conn->query($qry); //Execute the SQL and get the returned reuslts
        if (mysqli_num_rows($result)==0) {
            return true;
        }
        else
        {
            return false;
        }
    }
    if (checkPhoneNumberExists($phone, $email, $conn)){
        $qry = "UPDATE Shopper SET Name = '$name', Birthdate = '$formattedBirthDate', Address = '$address', Country='$country', Phone='$phone', ActiveStatus='$activeStatus' WHERE Email = '$email'";
        echo($qry);
        if ($conn->query($qry) === TRUE) {
            $_SESSION["SuccessMessage"] = "Profile successfully updated!";
            header("Location: index.php");
        }
        else{
            $_SESSION["ErrorMessage"] =  "Error updating record!" ;
            header("Location: update.php");
        }
    }
    else
    {
        $_SESSION["ErrorMessage"] = "Error! User with keyed in inputs already exists!";
        header("Location: update.php");
    }
    //Display Page layout header with updated session state and links
    include("header.php");
    include("footer.php");
?>