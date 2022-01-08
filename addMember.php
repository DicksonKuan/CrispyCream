<?php
    session_start();// Detect the current session

    //read the data input from previous page
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
    $dateEntered = date('m/d/Y', time());
    //Include the PHP file that establishes database connections handle: $conn
    include_once("mysql_conn.php");

    //Define the INSERT SQL statement 
    $qry = "INSERT INTO Shopper (Name,Birthdate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered)
            VALUES (?,?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($qry);

    $stmt->bind_param("sssssssssi", $name, $formattedBirthDate, $address, $country, $phone, $email, $password, $passwordQuestion, $passwordAnswer, 1);

    if($stmt->execute()){ //SQL Statement executed successfully
    //Retrieve the shopper ID assigned to the new shopper
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry); //Execute the SQL and get the returned reuslts
    while($row = $result->fetch_array()){
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }

    //Succesful message and ShopperID
    $Message = "Registration successful! <br>
                Your ShopperID is $_SESSION[ShopperID]<br>";
    //Save the Shopper Name in a session variable
    $_SESSION["ShopperName"] = $name;
    }
    else{ //Error message
        $Message = "<h3 style='color:red'>Error in inserting record</h3>";
    }

    //Release the resource allocated for prepared statement
    $stmt->close();
    //Close Database connection
    $conn->close();

    //Display Page layout header with updated session state and links
    include("header.php");
    echo $Message;
    include("footer.php");
?>