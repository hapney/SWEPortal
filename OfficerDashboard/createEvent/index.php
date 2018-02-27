<?php

    // Login Info
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        $redirectUrl = "http://ukyswe.com/signin/";
        echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
    }
 
    // Create Http context details
    $contextData = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($query)."\r\n");
 
    // Create context resource for our request
    $context = stream_context_create (array ( 'http' => $contextData ));
 
    $url = "http://ukyswe.com/PHP/getEventTypes.php";
    // Read page rendered as result of your POST request
    $eventTypeResult =  file_get_contents (
                    $url,  // page url
                    false,
                    $context);
    $eventTypeResult = json_decode($eventTypeResult, true);
      
      
  if (isset($_POST['eventName']) && isset($_POST['eventDate']) && isset($_POST['eventDescription'])
        && isset($_POST['pointsType']) && isset($_POST['eventType'])) {
            
      $name = $_POST['eventName'];
      $date = $_POST['eventDate'];
      $description = $_POST['eventDescription'];
      $pointsType = $_POST['pointsType'];
      $pointsPerHour = $_POST['pointsPerHour'];
      $totalPointsResult = $_POST['totalPoints'];
      
      if ($pointsType == "pointsHourly") {
          $pointsHourly = $pointsPerHour;
          $totalPoints = 0;
      }
      else {
          $pointsHourly = 0;
          $totalPoints = $totalPointsResult;
      }
      $eventTypeID = $_POST['eventType'];
      
      // Create map with request parameters
      $params2 = array ('name' => $name, 'date' => $date, 'description' => $description,
                        'pointsHourly' => $pointsHourly, 'totalPoints' => $totalPoints, 'eventTypeID' => $eventTypeID);
 
      // Build Http query using params
      $query2 = http_build_query ($params2);
 
      // Create Http context details
      $contextData2 = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($query2)."\r\n",
        'content'=> $query2 );
 
      // Create context resource for our request
      $context2 = stream_context_create (array ( 'http' => $contextData2 ));
 
      $url2 = "http://ukyswe.com/PHP/createEvent.php";
      // Read page rendered as result of your POST request
      $result2 =  file_get_contents (
                    $url2,  // page url
                    false,
                    $context2);
      $result2 = json_decode($result2, true);
      
      if ($result2['error'] == false) {
          
          // Redirect to Event Page
          $eventID = $result2['eventID'];
          $redirectUrl = "http://ukyswe.com/OfficerDashboard/viewEvent?eventID=" . $eventID;
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
    <link rel="icon" href="../../bootstrap/favicon.ico">

    <title>Create Event</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://ukyswe.com/OfficerDashboard/createEvent/createEvent.css" rel="stylesheet">
  </head>

  <body>
    <header>
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
              <a class="nav-link active" href="#">Create Event</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/createEventType">Create Event Type</a>
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
          <h1>Create Event</h1>

          <div class="div-createEvent">
              
            <form class="form-createEvent" name="createEventForm" action="./" method="POST" onsubmit="return validateForm()">
                <?php
                if ($result2['error'] == true) {
                    echo("<h2 style='color:#F00;'> Error: " . $result2['message'] . " Try again. </h2>");
                }
                else if ($result2['error'] == false && $result2['message'] != "") {
                    echo("<h2 style='color:#0F0;'> " . $result2['message'] . " </h2>");
                }
              ?>
              <h2 class="form-createEvent-heading">Enter Event Details</h2>
              <input type="text" name="eventName" class="form-control" placeholder="Event Name" required autofocus>
              
              Event Date [mm/dd/yyyy]
              <input type="date" name="eventDate" class="form-control" required>
	          <textarea name="eventDescription" class="form-control" required autofocus>Event Description</textarea>
              
              Event Type
              <select name="eventType" required>
                <?php
                  // Dynamically Fill the Event Types
                  foreach($eventTypeResult['eventTypes'] as $row) {
                    echo("<option value='" . $row["EventTypeID"] . "'>" . $row["Name"] . "</option>");
                  }
                ?>
                <option value="" disabled>If Other, Please Create New Event Type</option>
              </select>
              
              Points Type
              <select id="pointsType" name="pointsType" onchange="changePointsType()" required>
                <option value="pointsHourly">Points Per Hour</option>
                <option value="totalPoints">Total Points</option>
              </select>
              
              <input type="number" id="pointsPerHour" name="pointsPerHour" style="display:block;" class="form-control" placeholder="Points Per Hour (#.#)" step=0.1>
              <input type="number" id="totalPoints" name="totalPoints" style="display:none;" class="form-control" placeholder="Total Points (#.#)" step=0.1>
              
              <button class="btn btn-lg btn-primary btn-block" type="submit">Create Event</button>
	      **Note: Please make sure this event doesn't already exist.
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
    
    <script>
        function changePointsType() {
            var pointsTypeSelection = document.getElementById('pointsType');
            var pointsType = pointsTypeSelection.options[pointsTypeSelection.selectedIndex].value;
            
            var pointsPerHourBox = document.getElementById('pointsPerHour');
            var totalPointsBox = document.getElementById('totalPoints');
            
            if (pointsType == "pointsHourly") {
                // Hide the File Upload and Show Text Box
                pointsPerHourBox.style.display = "block";
                totalPointsBox.style.display = "none";
            }
            else if (pointsType == "totalPoints") {
                // Hide the Text Box and Show File Upload
                pointsPerHourBox.style.display = "none";
                totalPointsBox.style.display = "block";
            }
        }
    </script>
    
    <script>
        function validateForm() {
            
            validateDateStr = validateDate();
            validatePointsStr = validatePoints();
            
            if (validateDateStr != "" || validatePointsStr != "") {
                alert("Error: " + validateDateStr + validatePointsStr);
                return false;
            }
            return confirm('Are you sure everything is correct?');
        }
        
        function validateDate() {
            var eventDate = document.forms["createEventForm"]["eventDate"].value;
            
            // Strip the Whitespace
            eventDate = eventDate.replace(/ /g,'');
            
            // Check if Date is in Correct Format
            if (eventDate.length == 10 && (eventDate[2] == '/' && eventDate[5] == '/') || (eventDate[4] == '-' && eventDate[7] == '-')) {
                return "";
            }
            return "Date should be in format 'MM/DD/YYYY'. ";
        }
        
        function validatePoints() {
            var pointsType = document.forms["createEventForm"]["pointsType"].value;
            
            var pointsStr = "";
            var pointsVal = "";
            
            // Update Variables Based on Points Type Selected
            if (pointsType == "pointsHourly") {
                var pointsPerHour = document.forms["createEventForm"]["pointsPerHour"].value;
                
                var pointsStr = "'Points Per Hour'";
                var pointsVal = pointsPerHour;
                
            }
            else if (pointsType == "totalPoints") {
                var totalPoints = document.forms["createEventForm"]["totalPoints"].value;
                
                var pointsStr = "'Total Points'";
                var pointsVal = totalPoints;
            }
            
            // Return Appropriate Error Message
            if (pointsVal == "") {
                return pointsStr + " is missing a value."
            }
            if (pointsVal <= 0) {
                return pointsStr + " should be greater than 0."
            }
            return "";
        }
    </script>
    
  </body>
</html>
