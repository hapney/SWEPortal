<?php

    // Login Info
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        $redirectUrl = "http://ukyswe.com/signin/";
        echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
    }

  if (isset($_POST['eventTypeName']) && isset($_POST['eventTypeDescription'])) {
      $name = $_POST['eventTypeName'];
      $description = $_POST['eventTypeDescription'];
      
      // Create map with request parameters
      $params = array ('name' => $name, 'description' => $description);
 
      // Build Http query using params
      $query = http_build_query ($params);
 
      // Create Http context details
      $contextData = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($query)."\r\n",
        'content'=> $query );
 
      // Create context resource for our request
      $context = stream_context_create (array ( 'http' => $contextData ));
 
      $url = "http://ukyswe.com/PHP/createEventType.php";
      // Read page rendered as result of your POST request
      $result =  file_get_contents (
                    $url,  // page url
                    false,
                    $context);
      $result = json_decode($result, true);
      
      $output = "NONE";
      
      if ($result['error'] == false) {
          
          // Login Success
          //$redirectUrl = "http://ukyswe.com/OfficerDashboard/createEvent/";
          //echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
          // TODO: Redirect to Event Type Page
        
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
    <link rel="icon" href="../../bootstrap/favicon.ico">

    <title>Create Event Type</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://ukyswe.com/OfficerDashboard/createEventType/createEventType.css" rel="stylesheet">
  </head>

  <body>
    <header>
      <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="http://ukyswe.com/">UK SWE Membership Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/searchID">Check Points</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="http://ukyswe.com/OfficerDashboard/addAttendance">Membership Portal</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/logout/">Logout</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/addAttendance">Add Attendance <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/createEvent">Create Event</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Create Event Type</a>
            </li>
          </ul>

          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/searchEvents">Search Events</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/searchMembers">Search Members</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Create Event Type</h1>

          <div class="div-createEvent">
              
            <form class="form-createEvent" action="./" method="POST">
              <?php
                if ($result['error'] == true) {
                    echo("<h2 style='color:#F00;'> Error: " . $result['message'] . " Try again. </h2>");
                }
                else if ($result['error'] == false && $result['message'] != "") {
                    echo("<h2 style='color:#0F0;'> Success! </h2>");
                }
              ?>
              <h2 class="form-createEvent-heading">Enter Event Type Details</h2>
              <input type="text" name="eventTypeName" class="form-control" placeholder="Event Type Name" required autofocus>
	      <textarea name="eventTypeDescription" class="form-control" required autofocus>Event Type Description</textarea>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Create Event Type</button>
	      **Note: Please make sure this event type doesn't already exist.
            </form>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../bootstrap/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../bootstrap/assets/js/vendor/popper.min.js"></script>
    <script src="../../bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>
