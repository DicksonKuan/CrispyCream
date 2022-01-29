<?php
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
    $activeStatus = 1;
    session_start();// Detect the current session
    include_once("mysql_conn.php");
    function checkUserExists($phone, $email, $conn){
        $qry = "SELECT COUNT(*) AS Count FROM shopper WHERE PHONE = '$phone' OR Email = '$email';";
        $result = $conn->query($qry);
        
        while($row = $result->fetch_array()){
            $resultdata = $row['Count'];
        }
        echo ($resultdata);
        //Release the resource allocated for prepared statement
        if ($resultdata != 0) {
            return true;
        }
        return false;
    }
    $value = checkUserExists($phone, $email, $conn);
    if ($value == false) {
        //Include the PHP file that establishes database connections handle: $conn
        //Define the INSERT SQL statement 
        $qry = "INSERT INTO Shopper (Name, Birthdate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer, ActiveStatus)
        VALUES (?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($qry);

        $stmt->bind_param("sssssssssi", $name, $formattedBirthDate, $address, $country, $phone, $email, $password, $passwordQuestion, $passwordAnswer, $activeStatus);

        if($stmt->execute()){ //SQL Statement executed successfully
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry); //Execute the SQL and get the returned reuslts
        while($row = $result->fetch_array()){
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        //Succesful message and ShopperID
        $Message = "<span style='color:#00754e'>Registration successful! <br>
                    Your ShopperID is $_SESSION[ShopperID]<br></span>";
        //Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;//Retrieve the shopper ID assigned to the new shopper
        }
        else{ //Error message
        $Message = "<h3 style='color:red'>Error inserting record!</h3>";
        }
        //Release the resource allocated for prepared statement
        $stmt->close();
        //Close Database connection
        $conn->close();
    }
    else{
        $Message = "<h3 style='color:red'>Error! User with keyed in inputs already exists!</h3>";
    }

   
    //Display Page layout header with updated session state and links
    include("header.php");
    echo $Message;
    include("footer.php");
?>