<?php 
    include('addMember.php');
    $name = $_POST["fullname"];
    $birthDate = $_POST["birthdate"];
    $formattedBirthDate = date("Y-m-d", strtotime($birthDate));
    $address = $_POST["address"];
    $country = $_POST["country"];
    $phone = $_POST["phonenumber"];
    $email = $_POST["emailaddress"];
    $password = $_POST["password"];
    $passwordQuestion = $_POST["passwordquestion"];
    $passwordAnswer = $_POST["passwordanswer"];
    $activeStatus = $_POST["activestatus"];
    session_start();// Detect the current session
    include_once("mysql_conn.php");
    $qry = "UPDATE Shopper SET Name = '$name', Birthdate = '$formattedBirthDate', Address = '$address', Country='$country', Phone='$phone', Email='$phone', ActiveStatus='$activeStatus'"
    $value = checkUserExists($phone, $email, $conn);
    if ($value == false) {
        if ($conn->query($sql) === TRUE) {
            $Message = "<span style='color:#00754e'>Profile has be successfully updated!</span>";
        else{
            $Message = "<h3 style='color:red'>Error updating record!</h3>";
        }
    }
    else{
        $Message = "<h3 style='color:red'>Error! User with keyed in inputs already exists!</h3>";
    }
    //Display Page layout header with updated session state and links
    include("header.php");
    echo $Message;
    include("footer.php");
?>