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
    
    // Deal With Form
    if (isset($_POST['eventType']) || isset($_POST['eventName']) || isset($_POST['eventDate'])
        || isset($_POST['eventKeywords'])) {
         
      $eventType = $_POST['eventType'];
      $name = $_POST['eventName'];
      $date = $_POST['eventDate'];
      $eventKeywords = $_POST['eventKeywords'];
      
      // Create map with request parameters
      $params2 = array ('eventType' => $eventType, 'eventName' => $name, 'eventDate' => $date,
                        'eventKeywords' => $eventKeywords);
 
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
 
      $url2 = "http://ukyswe.com/PHP/searchEvents.php";
      // Read page rendered as result of your POST request
      $result2 =  file_get_contents (
                    $url2,  // page url
                    false,
                    $context2);
      $result2 = json_decode($result2, true);
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

    <title>Search Events</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./searchEvents.css" rel="stylesheet">
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
              <a class="nav-link active" href="#">Search Events</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://ukyswe.com/OfficerDashboard/searchMembers">Search Members</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Search Event</h1>

          <div class="div-createEvent">
              
            <form class="form-createEvent" action="./" method="POST">
              <h2 class="form-createEvent-heading">Select Any Fields [All Are Optional]</h2>
              Event Type
              <select id="eventType" name="eventType" required>
		<option disabled>Select Event Type</option>
                <?php
                  // Dynamically Fill the Event Types
                  foreach($eventTypeResult['eventTypes'] as $row) {
                    echo("<option value='" . $row["EventTypeID"] . "'>" . $row["Name"] . "</option>");
                  }
                ?>
                <option value="" disabled>If Other, Please Create New Event Type</option>
              </select>
              
	      <input type="text" id="eventName" name="eventName" class="form-control" placeholder="Event Name">
	      <input type="date" id="eventDate" name="eventDate" class="form-control" placeholder="Event Date [MM/DD/YYYY]">
	      <input type="text" id="eventKeywords" name="eventKeywords" class="form-control" placeholder="Event Keywords (separate by spaces or tabs)">
              <button class="btn btn-lg btn-primary btn-block" type="submit">Search Events</button>
	      **Note: If you are not getting results, try using less fields.
	      
	      <?php
	        if ($result2['error'] == false && $result2['events'] != "") {
                    
                $resultsTxt = "<br><hr><br><h2>Results</h2>";
                
                    $prevEventTypeID = "";
                    foreach($result2['events'] as $row) {
                        $eventTypeID = $row['EventTypeID'];
                        $eventTypeName = "Unknown Event Type";
                        
                        // Find the Event Type Name from the Event Type ID
                        foreach($eventTypeResult['eventTypes'] as $eventTypeRow) {
                            if ($eventTypeRow["EventTypeID"] == $eventTypeID)  {
                                $eventTypeName = $eventTypeRow["Name"];
                            }
                        }
                        
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
                                            <th>Select</th>
                                          </tr>
                                        </thead>
                                        <tbody>";
                        }
                        $resultsTxt .= "<tr>
                                <th>" . $row['Name'] . "</th>
                                <th>" . $row['Date'] . "</th>
                                <th>" . $row['Description'] . "</th>
                                <th><a href=\"http://ukyswe.com/OfficerDashboard/viewEvent?eventID=" . $row['EventID'] . "\">View/Edit</a></th>
                                </tr>";
                        
                        $prevEventTypeID = $eventTypeID;
                    }
	            echo($resultsTxt);
	        }
	      ?>
	      
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
  </body>
</html>
