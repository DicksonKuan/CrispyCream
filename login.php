<?php
//Detect the current session
session_start();
// Include the page layout header
unset($_SESSION["SuccessMessage"]);
unset($_SESSION["ErrorMessage"]);
include("header.php");
?>
<section class="vh-80">
  <div class="container-fluid py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card shadow" style="border-radius: 1rem;">
          <div class="row">
            <div class="col-md-6  d-none d-md-block">
              <img
                src="img/loginpicture.png"
                alt="login form"
                class="img-fluid h-100" style="border-radius: 1rem 0 0 1rem;"
              />
            </div>
            <div class="col-md-6 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="checkLogin.php" method="post">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <span class="h1 fw-bold mb-0">LOGIN</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                  <div class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control form-control-lg" required/>
                    <label class="form-label register-label" for="email">Email address</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control form-control-lg" required/>
                    <label class="form-label register-label" for="password">Password</label>
                  </div>

                  <div class="pt-1 mb-4">
                    <button type="submit" class="btn btn-lg btn-block" type="button" style="background-color:#00754e; color:white;">Login</button>
                  </div>
                  <?php
                    if (isset($_SESSION['LoginErrorMessage'])) {
                      echo($_SESSION['LoginErrorMessage']);
                  }
                  ?>
                  <a class="small text-muted mb-2" href="forgetPassword.php">Forgot password?</a>
                  <p class="mb-3" style="color: #393f81;">Don't have an account? <a href="register.php" style="color: #00754e;">Register here</a></p>
                  <a class="medium text-muted mb-5 pb-lg-2" href="index.php">Back to main menu</a>
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