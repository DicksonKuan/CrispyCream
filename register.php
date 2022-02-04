<?php 
// Detect the current session
session_start(); 
// Include the Page Layout header
include("header.php"); 
?>
<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
	if(document.register.password.value != document.register.confirmpassword.value){
        alert("Passwords not match!");
        return false;
    }

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

<div class="container register-form p-5 shadow" style="background-color: White; border-radius: .5rem;">
<h1 class=" fw-bold mb-0" style="text-align: center">SIGN UP</h1>
  <form class="mt-4" name="register" action="addMember.php" method="post" onsubmit="return validateForm()">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="fullname" class="register-label">Full Name</label>
        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required>
      </div>
      <div class="form-group col-md-6">
        <label for="birthdate" class="register-label">Birth Date</label>
        <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="BirthDate" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="emailaddress" class="register-label">Email</label>
        <input type="email" class="form-control" id="emailaddress" name="emailaddress" placeholder="Email" required>
      </div>
    </div>
    <div class="form-group">
      <label for="address" class="register-label">Address</label>
      <input type="text" class="form-control" id="address" name="address" placeholder="Address" maxlength="150" required>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="country" class="register-label">Country</label>
        <input type="text" class="form-control" id="country" name="country" placeholder="Country" maxlength="50" required> 
      </div>
      <div class="form-group col-md-6">
        <label for="phonenumber" class="register-label">Phone Number</label>
        <input type="tel" class="form-control" id="phonenumber" name="phonenumber" placeholder="Phone Number" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
          <label for="password" class="register-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
      </div>
      <div class="form-group col-md-6">
          <label for="confirmpassword" class="register-label">Confirm Password</label>
          <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" maxlength="20" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
          <label for="passwordquestion" class="register-label">Password Question</label>
          <input type="text" class="form-control" id="passwordquestion" name="passwordquestion" placeholder="Question for Password Reset" required>
      </div>
      <div class="form-group col-md-12">
          <label for="passwordanswer" class="register-label">Password Answer</label>
          <input type="text" class="form-control" id="passwordanswer" name="passwordanswer" placeholder="Password Question Answer" maxlength="20" required>
      </div>
    </div>
    
    <button type="submit" class="btn btn-lg btn-block" type="button"  style="background-color: #00754e; color:white; width:100%">Sign Up</button>
  </form>
  </br>
  <a class="medium text-muted pb-lg-2" href="index.php">Back to main menu</a>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>