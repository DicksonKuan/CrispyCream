<?php 
// Detect the current session
session_start(); 
// Include the page layout header
include("header.php");
$ShopperID = $_SESSION["ShopperID"];
include_once("mysql_conn.php");
$qry = "SELECT Password FROM Shopper WHERE ShopperID = $ShopperID";
$result = $conn->query($qry);
$row = $result->fetch_array();
$Password = $row["Password"];
if (isset($_SESSION["SuccessMessage"])) {
    unset($_SESSION["SuccessMessage"]);
}
if (isset($_SESSION["ErrorMessage"])) {
    unset($_SESSION["ErrorMessage"]);
}
?>
<script>
    function validate(){
        if(document.passwordForm.oldpassword.value != <?php echo $Password ?>){
            alert("Wrong Password!");
            return false;
        }
        if(document.passwordForm.newpassword.value != document.passwordForm.repeatnewpassword.value){
            alert("Passwords do not match!");
            return false;
        }
        return true;
    }
</script>
<section class="vh-80">
  <div class="container-fluid py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card shadow" style="border-radius: 1rem;">
          <div class="row">
            <div class="col-md-12 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

              <form name="passwordForm" action="updatePassword.php" method="post" onsubmit="return validate()">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <span class="h1 fw-bold mb-0">CHANGE PASSWORD</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Change your password</h5>

                  <div class="form-outline mb-4">
                    <input type="password" id="oldpassword" name="oldpassword" class="form-control form-control-lg" required/>
                    <label class="form-label" for="oldpassword">Current Password</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="password" id="newpassword" name="newpassword" class="form-control form-control-lg" required/>
                    <label class="form-label" for="newpassword">New Password</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="password" id="repeatnewpassword" name="repeatnewpassword" class="form-control form-control-lg" required/>
                    <label class="form-label" for="repeatnewpassword">Repeat Password</label>
                  </div>
                  <div class="pt-1 mb-4">
                    <button type="submit" class="btn btn-lg btn-block" type="button" style="background-color:#00754e; color:white;">Continue</button>
                  </div>

                  <a class="medium text-muted" href="index.php">Back to main menu</a>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include("footer.php");?>