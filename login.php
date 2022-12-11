<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login page</title>
  <link rel="stylesheet" href="assets/css/main/app.css" />
  <link rel="stylesheet" href="assets/css/pages/auth.css" />
  <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon" />
  <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png" />
</head>

<body>
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <div class="auth-logo">
            <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" style="width:100px; height:100px;" /></a>
          </div>
          <h1 class="auth-title" style="margin-top: -80px;">Log in</h1>

          <form action="./processing/login-processing.php" method="POST">
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="text" class="form-control form-control-xl" placeholder="Username" name="username" required autocomplete="off" />
              <div class="form-control-icon">
                <i class="bi bi-person"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="password" class="form-control form-control-xl" placeholder="Password" name="password" required />
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>
            </div>
            <?php
            if (isset($_SESSION['loginerror'])) {
              echo "<p class = \"font-weight-bold text-danger\">" . $_SESSION['loginerror'] . "</p>";
              unset($_SESSION['loginerror']);
            }
            ?>
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
              Log in
            </button>
          </form>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right" style=" display: flex; justify-content: center; align-items:center;"><img src="assets/images/bg/login_theme.jpg" alt="login theme" style="width: 450px; height: 400px;"></div>
      </div>
    </div>
  </div>
</body>

</html>
<?php
require "./components/foot.php";
?>