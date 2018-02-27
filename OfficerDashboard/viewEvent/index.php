<?php

    // LOGIN INFO
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        $redirectUrl = "http://ukyswe.com/signin/";
        echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
    }
    
    // Get the Event Details
    if (isset($_GET['eventID'])) {
      $eventID = $_GET['eventID'];
      
      // Create map with request parameters
      $eventParams = array ('eventID' => $eventID);
 
      // Build Http query using params
      $eventQuery = http_build_query ($eventParams);
 
      // Create Http context details
      $eventContextData = array ( 
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($eventQuery)."\r\n",
        'content'=> $eventQuery );
 
      // Create context resource for our request
      $eventContext = stream_context_create (array ( 'http' => $eventContextData ));
 
      $eventUrl = "http://ukyswe.com/PHP/getEventDetails.php";
      // Read page rendered as result of your POST request
      $eventResult =  file_get_contents (
                    $eventUrl,  // page url
                    false,
                    $eventContext);
      $eventResult = json_decode($eventResult, true);
      $eventDetails = $eventResult['eventDetails'];
      
      // Get the Attendance
      // Create map with request parameters
      $attendanceParams = array ('eventID' => $eventID);
 
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
 
      $attendanceUrl = "http://ukyswe.com/PHP/getEventAttendance.php";
      // Read page rendered as result of your POST request
      $attendanceResult =  file_get_contents (
                    $attendanceUrl,  // page url
                    false,
                    $attendanceContext);
      $attendanceResult = json_decode($attendanceResult, true);
      $eventAttendance = $attendanceResult['eventAttendance'];
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
    if (isset($_POST['eventName']) || isset($_POST['eventDate']) || isset($_POST['eventDescription']) 
            || isset($_POST['eventType']) || isset($_POST['pointsPerHour']) || isset($_POST['totalPoints'])) {
         
      $eventName = $_POST['eventName'];
      $eventDate = $_POST['eventDate'];
      $eventDescription = $_POST['eventDescription'];
      $eventType = $_POST['eventType'];
      $pointsPerHour = $_POST['pointsPerHour'];
      $totalPoints = $_POST['totalPoints'];
      
      // Create map with request parameters
      $formParams = array ('eventID' => $eventID, 'eventName' => $eventName, 'eventDate' => $eventDate, 'eventDescription' => $eventDescription,
                        'eventType' => $eventType, 'pointsPerHour' => $pointsPerHour, 'totalPoints' => $totalPoints);
 
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
 
      $formUrl = "http://ukyswe.com/PHP/updateEvent.php";
      // Read page rendered as result of your POST request
      $formResult =  file_get_contents (
                    $formUrl,  // page url
                    false,
                    $formContext);
      $formResult = json_decode($formResult, true);
      
      if ($formResult['error'] == false) {
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

    <title>View Event</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./viewEvent.css" rel="stylesheet">
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
          <h1>View Event: 
          <?php
            if ($eventDetails['Name'] != "") {
                echo($eventDetails['Name']); 
            }
            else {
                echo("Error- No Event Selected");
            }
          ?> </h1>

          <div class="div-viewEvent">
              
            <form class="form-viewEvent" action="./?eventID=<?php echo($eventID); ?>" method="POST" onsubmit="return validateForm()">
              <h2 class="form-viewEvent-heading">Details</h2>
              
              <p><b>Event Name: </b><?php echo($eventDetails['Name']); ?> <a onclick="showNameForm()" href="#">Edit</a> </p>
              <input type="text" id="eventName" name="eventName" style="display:none;" class="form-control" placeholder="Event Name">
              <button id="eventNameButton" name="eventNameButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Name</button>
              
              <p><b>Event Date: </b><?php echo($eventDetails['Date']); ?> <a onclick="showDateForm()" href="#">Edit</a> </p>
              <input type="date" id="eventDate" name="eventDate" style="display:none;" class="form-control" placeholder="Event Date [MM/DD/YYYY]">
              <button id="eventDateButton" name="eventDateButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Date</button>
              
              <p><b>Event Description: </b><?php echo($eventDetails['Description']); ?> <a onclick="showDescriptionForm()" href="#">Edit</a> </p>
              <textarea id="eventDescription" name="eventDescription" style="display:none;" class="form-control">Event Description</textarea>
              <button id="eventDescriptionButton" name="eventDescriptionButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Description</button>
              
              <p><b>Event Type: </b>
              <?php
                // Find the Event Type Name from the Event Type ID
                $eventTypeName = "Unable to Find Event Type.";
                foreach($eventTypeResult['eventTypes'] as $eventTypeRow) {
                    if ($eventTypeRow["EventTypeID"] == $eventDetails['EventTypeID'])  {
                        $eventTypeName = $eventTypeRow["Name"];
                    }
                }
                echo($eventTypeName); 
              ?> <a onclick="showEventTypeForm()" href="#">Edit</a> </p>
              <select id="eventType" name="eventType" style="display:none;">
                <option value="" disabled>--SELECT--</option>
                <?php
                  // Dynamically Fill the Event Types
                  foreach($eventTypeResult['eventTypes'] as $row) {
                    echo("<option value='" . $row["EventTypeID"] . "'>" . $row["Name"] . "</option>");
                  }
                ?>
                <option value="" disabled>If Other, Please Create New Event Type</option>
              </select>
              <button id="eventTypeButton" name="eventTypeButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Event Type</button>
              
              <p><b>Points Type: </b>
              <?php 
                if ($eventDetails['TotalPoints'] > 0) {
                    echo("Total Points [" . $eventDetails['TotalPoints'] . " Points]");
                }
                else if ($eventDetails['PointsHourly'] > 0) {
                    echo("Points Hourly [" . $eventDetails['PointsHourly'] . " Points Per Hour]");
                }
                else {
                    echo("Error Determining Points");
                }
              ?> 
              <a onclick="showPointForm()" href="#">Edit</a> </p>
              <select id="pointsType" name="pointsType" onchange="changePointsType()" style="display:none;">
                <option value="" disabled>--SELECT--</option>
                <option value="pointsHourly">Points Per Hour</option>
                <option value="totalPoints">Total Points</option>
              </select>
              <input type="number" id="pointsPerHour" name="pointsPerHour" style="display:none;" class="form-control" placeholder="Points Per Hour (#.#)" step=0.1>
              <input type="number" id="totalPoints" name="totalPoints" style="display:none;" class="form-control" placeholder="Total Points (#.#)" step=0.1>
              <button id="pointsTypeButton" name="pointsTypeButton" style="display:none;" class="btn btn-lg btn-primary btn-block" type="submit">Update Point Type</button>
	      
	      <br></br>
	      <h2>Attendance</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>StudentID</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <?php
                    if ($eventDetails['PointsHourly'] > 0) {
                        echo("<th>Hours</th>");
                    }
                  ?>
                  <th>Points</th>
                </tr>
              </thead>
              <tbody>
                <?php
        	        foreach($eventAttendance as $row) {
        	            echo("<tr>");
        	            echo("<th>" . $row['StudentID'] . "</th>");
                        echo("<th>" . $row['LastName'] . "</th>");
        	            echo("<th>" . $row['FirstName'] . "</th>");
        	            if ($eventDetails['PointsHourly'] > 0) {
        	                echo("<th>" . $row['Hours'] . "</th>");
        	            }
        	            echo("<th>" . $row['Points'] . "</th>");
        	            echo("</tr>");
        	        }
        	    ?>
              </tbody>
            </table>
          </div>
	        
	        
	        
	        
            </form>
            
          </div>

	  <!-- Here, have a list of the query results with a SELECT button next to each one. Once selected, page
		will redirect to a more thorough page for the event. -->


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
            // Edit Name
            var nameBox = document.getElementById('eventName');
            var nameSubmit = document.getElementById('eventNameButton');
            nameBox.value = "";
            nameBox.style.display = "none";
            nameSubmit.style.display = "none";
            
            // Edit Date
            var eventDate = document.getElementById('eventDate');
            var eventDateSubmit = document.getElementById('eventDateButton');
            eventDate.value = "";
            eventDate.style.display = "none";
            eventDateSubmit.style.display = "none";
            
            // Edit Description
            var eventDescription = document.getElementById('eventDescription');
            var eventDescriptionSubmit = document.getElementById('eventDescriptionButton');
            eventDescription.value = "";
            eventDescription.style.display = "none";
            eventDescriptionSubmit.style.display = "none";
            
            // Edit Event Type
            var eventType = document.getElementById('eventType');
            var eventTypeSubmit = document.getElementById('eventTypeButton');
            eventType.style.display = "none";
            eventTypeSubmit.style.display = "none";
            
            // Edit Points Type
            var pointsType = document.getElementById('pointsType');
            var pointsTypeSubmit = document.getElementById('pointsTypeButton');
            var pointsPerHour = document.getElementById('pointsPerHour');
            var totalPoints = document.getElementById('totalPoints');
            pointsPerHour.value = "";
            totalPoints.value = "";
            pointsType.style.display = "none";
            pointsTypeSubmit.style.display = "none";
            pointsPerHour.style.display = "none";
            totalPoints.style.display = "none";
        }
    
        function showNameForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var nameBox = document.getElementById('eventName');
            var nameSubmit = document.getElementById('eventNameButton');
            
            nameBox.style.display = "block";
            nameSubmit.style.display = "block";
        }
        
        function showDateForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var eventDate = document.getElementById('eventDate');
            var eventDateSubmit = document.getElementById('eventDateButton');
            
            eventDate.style.display = "block";
            eventDateSubmit.style.display = "block";
        }
        
        function showDescriptionForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var eventDescription = document.getElementById('eventDescription');
            var eventDescriptionSubmit = document.getElementById('eventDescriptionButton');
            
            eventDescription.style.display = "block";
            eventDescriptionSubmit.style.display = "block";
        }
        
        function showEventTypeForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var eventType = document.getElementById('eventType');
            var eventTypeSubmit = document.getElementById('eventTypeButton');
            
            eventType.style.display = "block";
            eventTypeSubmit.style.display = "block";
        }
        
        function showPointForm() {
            
            // Hide All Forms
            hideEditForms();
            
            var pointsType = document.getElementById('pointsType');
            var pointsTypeSubmit = document.getElementById('pointsTypeButton');
            var pointsPerHour = document.getElementById('pointsPerHour');
            
            pointsType.style.display = "block";
            pointsPerHour.style.display = "block";
            pointsTypeSubmit.style.display = "block";
        }
    </script>
    
    <script>
        function changePointsType() {
            var pointsTypeSelection = document.getElementById('pointsType');
            var pointsType = pointsTypeSelection.options[pointsTypeSelection.selectedIndex].value;
            
            var pointsPerHourBox = document.getElementById('pointsPerHour');
            var totalPointsBox = document.getElementById('totalPoints');
            
            pointsPerHourBox.value = "";
            totalPointsBox.value = "";
            
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
            var nameBox = document.getElementById('eventName');
            var eventDate = document.getElementById('eventDate');
            var eventDescription = document.getElementById('eventDescription');
            var eventType = document.getElementById('eventType');
            var pointsType = document.getElementById('pointsType');
            
            if (nameBox.style.display == "block") {
                var nameTxt = nameBox.value;
                if (nameTxt == "") {
                    alert("Name Text Cannot Be Empty");
                    return false;
                }
            }
            else if (eventDate.style.display == "block") {
                if (!validateDate()) {
                    alert("Date Format Is Incorrect");
                    return false;
                }
            }
            else if (eventDescription.style.display == "block") {
                var eventDescriptionTxt = eventDescription.value;
                if (eventDescriptionTxt == "") {
                    alert("Event Description Cannot Be Empty");
                    return false;
                }
            }
            else if (eventType.style.display == "block") {
                var eventTypeSelection = eventType.options[eventType.selectedIndex].value;
                if (eventTypeSelection == "") {
                    alert("Please Choose an Event Type");
                    return false;
                }
            }
            else if (pointsType.style.display == "block") {
                if (!validatePoints()) {
                    alert("Points Should Be Greater Than 0");
                    return false
                }
            }
            
            return true;
        }
        
        function validateDate() {
            var eventDate = document.getElementById('eventDate');
            var eventDateTxt = eventDate.value;
            
            // Strip the Whitespace
            eventDateTxt = eventDateTxt.replace(/ /g,'');
            
            // Check if Date is in Correct Format
            if (eventDateTxt.length == 10 && eventDateTxt[2] == '/' && eventDateTxt[5] == '/') {
                return true;
            }
            return false;
        }
        
        function validatePoints() {
            
            var pointsTypeSelection = pointsType.options[pointsType.selectedIndex].value;
            var pointsVal = "";
            
            if (pointsTypeSelection == "pointsHourly") {
                pointsVal = document.getElementById('pointsPerHour').value;
            }
            else if (pointsTypeSelection == "totalPoints") {
                pointsVal = document.getElementById('totalPoints').value;
            }
            
            // Error Checking
            if (pointsVal == "" || pointsVal <= 0) {
                return false
            }
            return true;
        }
        
    </script>
    
  </body>
</html>