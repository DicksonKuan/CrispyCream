<?php
//Detect the current session
session_start();
// Include the page layout header
include("header.php");
?>
<section class="vh-80">
  <div class="container-fluid py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card shadow" style="border-radius: 1rem;">
          <div class="row">
            <div class="col-md-12 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="resetPassword.php" method="post">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <span class="h1 fw-bold mb-0">FORGET PASSWORD</span>
                  </div>

                  <h5 class="fw-normal mb-2 pb-2" style="letter-spacing: 1px;">Reset your password</h5>
                  <p class="fw-normal pb-2" style="letter-spacing: 1px;">Please answer the displayed question to reset your password</p>
                  <div class="form-outline mb-4">
                    <input type="text" id="pwdquestion" value="<?php echo $_SESSION["PwdQuestion"] ?>" name="pwdquestion" class="form-control form-control-lg" readonly/>
                    <label class="form-label" for="pwdquestion">Question</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="text" id="pwdquestionanswer"  name="pwdquestionanswer" class="form-control form-control-lg" required/>
                    <label class="form-label" for="pwdquestionanswer">Answer</label>
                  </div>
                  <h5 style="color:red">
                    <?php 
                            if (isset($_SESSION["ErrorMessage"])) {
                                echo($_SESSION["ErrorMessage"]);
                            }    
                    ?>
                   </h5>
                  <div class="pt-1 mb-4">
                    <button type="submit" class="btn btn-lg btn-block" type="button" style="background-color:#00754e; color:white;">Continue</button>
                  </div>

                  <a class="medium text-muted" href="forgetPassword.php">Back</a>
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