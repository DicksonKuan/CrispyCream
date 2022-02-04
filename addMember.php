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
        $Message = "<center><div class='my-3'>
                    <i class='fa fa-user-plus' style='font-size:50px;color:#00754e'></i>
                    <h3 class='pt-4' style='color:#00754e; align-items:center;'>Registration successful!</br>
                    Your ShopperID is $_SESSION[ShopperID]<br></h3>
                    </div></center>";
        //Save the Shopper Name in a session variable
        $_SESSION["ShopperName"] = $name;//Retrieve the shopper ID assigned to the new shopper
        }
        else{ //Error message
            $Message = "<center><div class='my-3'>
            <i class='fa fa-user-times' style='font-size:50px;color:red'></i>
            <h3 class='pt-4' style='color:red; align-items:center;'>Error Inserting Record!</h3>
            <a class='btn btn-danger px-5 my-2' href='register.php'>Back</a>
            </div></center>";
        }
        //Release the resource allocated for prepared statement
        $stmt->close();
        //Close Database connection
        $conn->close();
    }
    else{
        $Message = "<center><div class='my-3'>
        <i class='fa fa-user-times' style='font-size:50px;color:red'></i>
        <h3 class='pt-4' style='color:red; align-items:center;'>User with entered details already exists!</h3>
        <a class='btn btn-danger px-5 my-2' href='register.php'>Back</a>
        </div></center>";
    }

   
    //Display Page layout header with updated session state and links
    include("header.php");
    echo $Message;

    include("footer.php");
?>