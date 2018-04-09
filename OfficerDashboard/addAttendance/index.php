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

  if (isset($_POST['eventType']) && isset($_POST['event']) && isset($_POST['column1']) && isset($_POST['column2'])
        && isset($_POST['column3']) && isset($_POST['column4']) /* && isset($_POST['column5']) */ && isset($_POST['attendanceText'])) {
            
      $eventType = $_POST['eventType'];
      $event = $_POST['event'];
      $col1 = $_POST['column1'];
      $col2 = $_POST['column2'];
      $col3 = $_POST['column3'];
      $col4 = $_POST['column4'];
      /*$col5 = $_POST['column5'];*/
      $attendanceText = $_POST['attendanceText'];
      
      // Create map with request parameters
      $params2 = array ('eventType' => $eventType, 'event' => $event, 'col1' => $col1, 'col2' => $col2, 'col3' => $col3,
                        'col4' => $col4, /*'col5' => $col5,*/ 'attendanceText' => $attendanceText);
 
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
 
      $url2 = "http://ukyswe.com/PHP/addAttendance.php";
      // Read page rendered as result of your POST request
      $result2 =  file_get_contents (
                    $url2,  // page url
                    false,
                    $context2);
      $result2 = json_decode($result2, true);
      
      if ($result2['error'] == false) {
          
          // Membership Success
          //$redirectUrl = "http://ukyswe.com/OfficerDashboard/createEvent/";
          //echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
          // TODO: Redirect to Event Page
          
          $resultsTxt = "";
          if (count($result2['createStudentSuccess']) > 0) {
              $resultsTxt .= "<h4>Successfully Created Students:</h4>";
              foreach($result2['createStudentSuccess'] as $css) {
                  $resultsTxt .= "<h4>*****" . $css . "</h4>";
              }
          }
          if (count($result2['createStudentFailure']) > 0) {
              $resultsTxt .= "<h4>Failure to Create Students:</h4>";
              foreach($result2['createStudentFailure'] as $css) {
                  $resultsTxt .= "<h4>*****" . $css . "</h4>";
              }
          }
          if (count($result2['addAttendanceSuccess']) > 0) {
              $resultsTxt .= "<h4>Successfully Added Attendance for:</h4>";
              foreach($result2['addAttendanceSuccess'] as $css) {
                  $resultsTxt .= "<h4>*****" . $css . "</h4>";
              }
          }
          if (count($result2['addAttendanceFailure']) > 0) {
              $resultsTxt .= "<h4>Failure to Add Attendance for:</h4>";
              foreach($result2['addAttendanceFailure'] as $css) {
                  $resultsTxt .= "<h4>*****" . $css . "</h4>";
              }
          }
          
          echo('<script>
                    window.onload = function() {
                    var resultsDiv = document.getElementById(\'resultsDiv\');
                    
                    resultsDiv.innerHTML ="' . $resultsTxt . '";
                    
                    resultsDiv.style.display = "block"; };
                </script>');
      }
      else if ($result2['error'] == true) {
          
          $resultsTxt = "<h4>Error: " . $result2['message'] . "</h4>";
          
          echo('<script>
                    window.onload = function() {
                    var resultsDiv = document.getElementById(\'resultsDiv\');
                    
                    resultsDiv.innerHTML ="' . $resultsTxt . '";
                    
                    resultsDiv.style.display = "block"; };
                </script>');
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

    <title>Add Attendance</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://ukyswe.com/OfficerDashboard/addAttendance/addAttendance.css" rel="stylesheet">
  </head>

  <body id="body">
    <header>
      <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="http://ukyswe.com">UK SWE Membership Portal</a>
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
              <a class="nav-link active" href="#">Add Attendance <span class="sr-only">(current)</span></a>
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

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Add Event Attendance</h1>

          <div class="div-createEvent">
              
            <form class="form-createEvent" name="attendanceForm" action="./" method="POST" onsubmit="return validateForm()">
              <h2 class="form-createEvent-heading">Enter Attendance List</h2>
              Event Type
              <select name="eventType" id="eventType" onchange="changeEventType(this.value)" required>
                <option value="" disabled>--Select--</option>
                <?php
                  // Dynamically Fill the Event Types
                  foreach($eventTypeResult['eventTypes'] as $row) {
                    echo("<option value='" . $row["EventTypeID"] . "'>" . $row["Name"] . "</option>");
                  }
                ?>
                <option value="" disabled>If Other, Please Create New Event Type</option>
              </select>

	      Event
              <select name="event" id="event" required>
                <option value="" disabled>Please Select an Event Type Above</option>
              </select>
              

	      <div class="columnOrder">
    	      <h5>Choose Order of Columns (Not All Are Needed)</h5>
    	      <select name="column1" required>
    		    <option value="" disabled>Column 1</option>
                <option value="StudentID">Student ID</option>
                <option value="FirstName">First Name</option>
    	        <option value="LastName">Last Name</option>
              </select>
    
    	      <select name="column2" required>
    		    <option value="" disabled>Column 2</option>
                <option value="StudentID">Student ID</option>
                <option value="FirstName">First Name</option>
                <option value="LastName">Last Name</option>
              </select>
    
    	      <select name="column3" required>
    		    <option value="" disabled>Column 3</option>
                <option value="StudentID">Student ID</option>
                <option value="FirstName">First Name</option>
                <option value="LastName">Last Name</option>
              </select>
    
    	      <select name="column4" required>
    		    <option value="" disabled>Column 4</option>
                <option value="Hours">Hours Volunteered [optional]</option>
    		    <option value="">None</option>
              </select>
	      </div>

	      <textarea name="attendanceText" id="attendanceText" style="display:block;" class="form-control" autofocus>
Please enter each attendee on a new line with white space (tabs or spaces) between each column.</textarea>
	      
          <button class="btn btn-lg btn-primary btn-block" type="submit">Add Attendance</button>
            
            <div style="display:none;" id="resultsDiv" name="resultsDiv">
            </div>
            
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
        function changeEventType(eventTypeID) {
            var eventTypeSelection = document.getElementById('eventType');
            var eventType = eventTypeSelection.options[eventTypeSelection.selectedIndex].value;
            
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Typical action to be performed when the document is ready:
                    document.getElementById('event').innerHTML = xhttp.responseText;
                }
            };
            xhttp.open("GET", "./getEvents.php?eventTypeID=" + eventTypeID, true);
            xhttp.send();
        }
    </script>
    
    <script>
        function validateForm() {
            if(validateColumns() && validateBox()) {
                return confirm('Are you sure everything is correct?');
            }
            return false;
        }
        
        function validateColumns() {
            var col1 = document.forms["attendanceForm"]["column1"].value;
            var col2 = document.forms["attendanceForm"]["column2"].value;
            var col3 = document.forms["attendanceForm"]["column3"].value;
            var col4 = document.forms["attendanceForm"]["column4"].value;
            //var col5 = document.forms["attendanceForm"]["column5"].value;
            
            // Checks if multiple columns have the same descriptions
            var alertStr = "";
            if (col1 == col2 || col2 == col3 || col1 == col3 /* || (col4 == col5 && col4 != "") */) {
                // Error: User has multiple columns with same description
                alertStr += " Multiple columns with same description.";
            }
            
            // Makes sure user described the first three columns
            if (col1 == "" || col2 == "" || col3 == "") {
                // Error: User hasn't filled out the first three columns
                alertStr += " First three columns must exist."
            }
            
            /*if (col4 == "" && col5 != "") {
                alertStr += " Cannot skip column 4."
            }*/
            
            if (alertStr != "") {
                alert("Error:" + alertStr);
                return false;
            }
            
            return true;
        }
        
        function validateBox() {
            var box = document.forms["attendanceForm"]["attendanceText"].value;
            
            if (box == "") {
                alert("Error: Attendance Text Should Not Be Empty.");
                return false;
            }
            return true;
        }
    </script>
    
    <script>
        function showResultsDiv() {
            var resultsDiv = document.getElementById('resultsDiv');
            resultsDiv.style.display = "block";
        }
    </script>
    
  </body>
</html>

