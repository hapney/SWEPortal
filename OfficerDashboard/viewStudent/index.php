<?php

    // LOGIN INFO
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        //$redirectUrl = "http://ukyswe.com/signin/";
        //echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
        // Shift Everything Over to the Left
    }
    
    // Get the Student Details
    if (isset($_GET['studentID'])) {
      $studentID = $_GET['studentID'];
      
      // Create map with request parameters
      $studentParams = array ('studentID' => $studentID);
 
      // Build Http query using params
      $studentQuery = http_build_query ($studentParams);
 
      // Create Http context details
      $studentContextData = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($studentQuery)."\r\n",
        'content'=> $studentQuery );
 
      // Create context resource for our request
      $studentContext = stream_context_create (array ( 'http' => $studentContextData ));
 
      $studentUrl = "http://ukyswe.com/PHP/getStudentDetails.php";
      // Read page rendered as result of your POST request
      $studentResult =  file_get_contents (
                    $studentUrl,  // page url
                    false,
                    $studentContext);
      $studentResult = json_decode($studentResult, true);
      $studentDetails = $studentResult['studentDetails'];
      
      // Get the Attendance
      // Create map with request parameters
      $attendanceParams = array ('studentID' => $studentID);
 
      // Build Http query using params
      $attendanceQuery = http_build_query ($attendanceParams);
 
      // Create Http context details
      $attendanceContextData = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($attendanceQuery)."\r\n",
        'content'=> $attendanceQuery );
 
      // Create context resource for our request
      $attendanceContext = stream_context_create (array ( 'http' => $attendanceContextData ));
 
      $attendanceUrl = "http://ukyswe.com/PHP/getAttendedEvents.php";
      // Read page rendered as result of your POST request
      $attendanceResult =  file_get_contents (
                    $attendanceUrl,  // page url
                    false,
                    $attendanceContext);
      $attendanceResult = json_decode($attendanceResult, true);
      $attendedEvents = $attendanceResult['attendedEvents'];
    }

    // EVENT TYPE RESULTS
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

    // Deal With Form
    if (isset($_POST['firstName']) || isset($_POST['lastName'])) {
        
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      
      // Create map with request parameters
      $formParams = array ('studentID' => $studentID, 'firstName' => $firstName, 'lastName' => $lastName);
 
      // Build Http query using params
      $formQuery = http_build_query ($formParams);
 
      // Create Http context details
      $formContextData = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($formQuery)."\r\n",
        'content'=> $formQuery );
 
      // Create context resource for our request
      $formContext = stream_context_create (array ( 'http' => $formContextData ));
 
      $formUrl = "http://ukyswe.com/PHP/updateStudent.php";
      // Read page rendered as result of your POST request
      $formResult =  file_get_contents (
                    $formUrl,  // page url
                    false,
                    $formContext);
      $formResult = json_decode($formResult, true);
      if ($formResult['error'] == false) {
          $redirectUrl = "http://ukyswe.com/OfficerDashboard/viewStudent?studentID=" . $studentID;
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

    <title>View Student</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./viewStudent.css" rel="stylesheet">
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
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) { ?>
                <li class="nav-item">
                  <a class="nav-link" href="http://ukyswe.com/signin">Login</a>
                </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/searchID">Check Points</a>
            </li>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
            <li class="nav-item">
              <a class="nav-link active" href="http://ukyswe.com/OfficerDashboard/addAttendance">Membership Portal</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/logout/">Logout</a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/addAttendance">Add Attendance <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/createEvent">Create Event</a>
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
        <?php } ?>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>View Student: 
          <?php
            if ($studentDetails['FirstName'] != "") {
                echo("[" . $studentDetails['StudentID'] . "] " . $studentDetails['LastName'] . ", " . $studentDetails['FirstName']); 
            }
            else {
                echo("Error- No Student Selected");
            }
          ?> </h1>

          <div class="div-viewStudent">
              
            <form class="form-viewStudent" action="./?studentID=<?php echo($studentID); ?>" method="POST" onsubmit="return validateForm()">
              <h2 class="form-viewStudent-heading">Details</h2>
              
              <p><b>Student ID: </b><?php echo($studentDetails['StudentID']); ?></p>
              
              <p><b>Last Name: </b><?php echo($studentDetails['LastName']); ?> 
              <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
              <a onclick="showLastNameForm()" href="#">Edit</a> 
              <?php } ?>
              </p>
              <input type="text" id="lastName" name="lastName" style="display:none;" class="form-control" placeholder="Last Name">
              <button id="lastNameButton" name="lastNameButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Name</button>
              
              <p><b>First Name: </b><?php echo($studentDetails['FirstName']); ?> 
              <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
              <a onclick="showFirstNameForm()" href="#">Edit</a> 
              <?php } ?>
              </p>
              <input type="text" id="firstName" name="firstName" style="display:none;" class="form-control" placeholder="First Name">
              <button id="firstNameButton" name="firstNameButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Name</button>
              
              <p><b>Total Hours: </b><?php echo($studentDetails['TotalHours']); ?></p>
              
              <p><b>Total Points: </b><?php echo($studentDetails['TotalPoints']); ?></p>
          
          <hr><br>
	      <h2>Attended Events</h2>
          <?php
                    
            $resultsTxt = "";
            $prevEventTypeID = "";
            foreach($attendedEvents as $row) {
                $eventTypeName = $row['EventTypeName'];
                $eventTypeID = $row['EventTypeID'];
                        
                if ($prevEventTypeID != $eventTypeID) {
                    if ($prevEventTypeID != "") {
                        $resultsTxt .= "</tbody></table><br>";
                    }
                    $resultsTxt .= "<h4>" . $eventTypeName . "</h4>
                        <div class=\"table-responsive\">
                            <table class=\"table table-striped\">
                                <thead>
                                  <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Hours</th>
                                    <th>Total Points</th>";
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {              
                        $resultsTxt .= "<th>Select</th>";
                    }
                    $resultsTxt .= "</tr>
                                </thead>
                                <tbody>";
                }
                $resultsTxt .= "<tr>
                                    <th>" . $row['Name'] . "</th>
                                    <th>" . $row['Date'] . "</th>
                                    <th>" . $row['Description'] . "</th>
                                    <th>";
                if ($row['Hours'] == -1) {
                    $resultsTxt .= "--</th><th>" . $row['TotalPoints'] . "</th>";
                }
                else {
                    $resultsTxt .= $row['Hours'] . "</th><th>" . ($row['PointsHourly'] * $row['Hours']) . "</th>";
                }
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {  
                    $resultsTxt .=      "<th><a href=\"http://ukyswe.com/OfficerDashboard/viewEvent?eventID=" . $row['EventID'] . "\">View/Edit</a></th>";
                }
                $resultsTxt .= "</tr>";
                        
                $prevEventTypeID = $eventTypeID;
            }
            
	        echo($resultsTxt);
	      ?>
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
        function hideEditForms() {
            
            // Edit First Name
            var firstName = document.getElementById('firstName');
            var firstNameSubmit = document.getElementById('firstNameButton');
            firstName.value = "";
            firstName.style.display = "none";
            firstNameSubmit.style.display = "none";
            
            // Edit Last Name
            var lastName = document.getElementById('lastName');
            var lastNameSubmit = document.getElementById('lastNameButton');
            lastName.value = "";
            lastName.style.display = "none";
            lastNameSubmit.style.display = "none";
        }
        
        function showFirstNameForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var firstName = document.getElementById('firstName');
            var firstNameSubmit = document.getElementById('firstNameButton');
            
            firstName.style.display = "block";
            firstNameSubmit.style.display = "block";
        }
        
        function showLastNameForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var lastName = document.getElementById('lastName');
            var lastNameSubmit = document.getElementById('lastNameButton');
            
            lastName.style.display = "block";
            lastNameSubmit.style.display = "block";
        }
        
    </script>
    
    <script>
        function validateForm() {
            var firstName = document.getElementById('firstName');
            var lastName = document.getElementById('lastName');
            
            if (firstName.style.display == "block") {
                var firstNameTxt = firstName.value;
                if (firstNameTxt == "") {
                    alert("First Name Cannot Be Empty");
                    return false;
                }
            }
            else if (lastName.style.display == "block") {
                var lastNameTxt = lastName.value;
                if (lastNameTxt == "") {
                    alert("Last Name Cannot Be Empty");
                    return false;
                }
            }
            return true;
        }
        
    </script>
    
  </body>
</html>