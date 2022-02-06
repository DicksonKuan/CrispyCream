<?php 
// Detect the current session
session_start(); 
$ShopperID = $_SESSION["ShopperID"];
include_once("mysql_conn.php");
$qry = "SELECT Name, BirthDate, Address, Country, Phone, Email, Password, ActiveStatus FROM Shopper WHERE ShopperID = $ShopperID";

$result = $conn->query($qry);
$row = $result->fetch_array();
$Name = $row["Name"];
$Birthdate = $row["BirthDate"];
$Address = $row["Address"];
$Country = $row["Country"];
$Phone = $row["Phone"];
$Email = $row["Email"];
$ActiveStatus = $row["ActiveStatus"];
$_SESSION["Email"] = $Email;
$_SESSION["Phone"] = $Phone;
// Include the Page Layout header
include("header.php"); 
?>
<script type="text/javascript">
function validateForm()
{
	// To Do 2 - Check if telephone number entered correctly
	//           Singapore telephone number consists of 8 digits,
	//           start with 6, 8 or 9
    if(document.register.phonenumber.value!= ""){
        var str = document.register.phonenumber.value;
        if(str.length!= 8){
            alert("Please enter a 8-digit phone number. ");
            return false; //Cancel submission
        }
        else if(str.substr(0,1)!="6" &&
                str.substr(0,1)!="8" &&
                str.substr(0,1)!="9"){
                    alert("Phone number in Singapore should start with 6,8 or 9.");
                    return false;
                }
        return true; //No error found
    }
    return true;  // No error found
}
</script>

<div class="container">
<h1 class="fw-bold mb-1 text-center">UPDATE PROFILE</h1>
  <form name="register" action="updateMember.php" method="post" onsubmit="return validateForm();">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="fullname" class="register-label">Full Name</label>
        <?php 
          echo ("<input type='text' class='form-control' id='fullname' name='fullname' placeholder='Full Name' value='$Name'>");
        ?>
      </div>
      <div class="form-group col-md-6">
        <label for="emailaddress" class="register-label">Email</label>
        <?php 
          echo ("<input type='email' class='form-control' id='emailaddress' name='emailaddress' placeholder='Email' value='$Email' readonly>");
        ?>
      </div>
    </div>
    <div class="form-group">
      <label for="address" class="register-label">Address</label>
      <?php 
        echo ("<input type='text' class='form-control' id='address' name='address' placeholder='Address' maxlength='150' value='$Address'>");
      ?>
    </div>
    
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="country" class="register-label">Country</label>
        <?php 
          echo ("<input type='text' class='form-control' id='country' name='country' placeholder='Country' maxlength='50' value='$Country'>");
        ?>
      </div>
      <div class="form-group col-md-4">
        <label for="phonenumber" class="register-label">Phone Number</label>
        <?php 
          echo ("<input type='tel' class='form-control' id='phonenumber' name='phonenumber' placeholder='Phone Number' value='$Phone'>");
        ?>
      </div>
      <div class="form-group col-md-4">
        <label for="birthdate" class="register-label">Birth Date</label>
        <?php 
          echo ("<input type='date' class='form-control' id='birthdate' name='birthdate' placeholder='BirthDate' value='$Birthdate' readonly>");
        ?>
      </div>
    </div>
    <div class="form-row">
      <div class="form-check col-md-4 ml-4">
        <input class="form-check-input " type="radio" value="1" id="activestatus" name="activestatus" required>
          <label class="form-check-label" for="activestatus">
            Active
          </label>
      </div>
      <div class="form-check col-md-8 ml-4">
        <input class="form-check-input " type="radio" value="0" id="inactivestatus" name="activestatus">
          <label class="form-check-label" for="activestatus">
            Inactive
          </label>
      </div>
    </div>
    </div>
    <br>
    <div class="form-group">
    <button type="submit" type="button" class="btn btn-lg btn-block" style="background-color: #00754e; color:white;">Update</button>
    </div>
  </form>
  </br>
  <a class="medium text-muted mb-5 pb-lg-2" href="index.php">Back to main menu</a>
  </br>
</div>
  
<?php 
if ($ActiveStatus == 1){
  echo '<script type="text/JavaScript"> 
    document.getElementById("activestatus").checked = true;
 </script>';
}
else{
  echo '<script type="text/JavaScript"> 
  document.getElementById("inactivestatus").checked = true;
</script>';
 }

// Include the Page Layout footer
include("footer.php"); 
?>