<?php
    session_start();
    
  if (isset($_POST['inputEmail']) && isset($_POST['inputPassword'])) {
      
      $email = $_POST['inputEmail'];
      $password = $_POST['inputPassword'];
      
      // Encrypt the Password
      $password = hash('md5', $password);
      
      // Authenticate the User
      $url = "http://ukyswe.com/PHP/authenticate.php?username=$email&password=$password";
      $result = file_get_contents($url, false, $info);
      $result = json_decode($result, true);
      
      if ($result['error'] == false) {
          // Login Success
          
          // Set Session Variables
          $_SESSION['loggedin'] = true;
          $_SESSION['email'] = $email;
          
          // Redirect to Officer Dashboard
          $redirectUrl = "http://ukyswe.com/OfficerDashboard/addAttendance/";
          echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
      }
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../bootstrap/favicon.ico">

    <title>Login to SWE Membership Portal</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">

      <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="http://ukyswe.com">UK SWE Membership Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="http://ukyswe.com/signin">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/searchID">Check Points</a>
            </li>
          </ul>
        </div>
      </nav>

      <form class="form-signin" action="./" method="POST">
        <h2 class="form-signin-heading">
            <?php 
            if ($result['error'] == true) { 
              echo("Error: Try Again"); 
            }
            else {
              echo("Please sign in");
            }
            ?></h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->
  </body>
</html>
