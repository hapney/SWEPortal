<?php
    // Login Info
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        $redirectUrl = "http://ukyswe.com/signin/";
        echo("<script type='text/javascript'>window.location.href = '$redirectUrl';</script>");
    }

    // Deal With Form
    $formSuccess = -1;
    if (isset($_POST['studentID']) || isset($_POST['firstName']) || isset($_POST['lastName'])
        || isset($_POST['pointComparison'])) {
         
      $studentID = $_POST['studentID'];
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $pointComparison = $_POST['pointComparison'];
      $points = $_POST['points'];
      
      // Create map with request parameters
      $formParams = array ('studentID' => $studentID, 'firstName' => $firstName, 'lastName' => $lastName,
                        'pointComparison' => $pointComparison, 'points' => $points);
 
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
 
      $formUrl = "http://ukyswe.com/PHP/searchStudents.php";
      // Read page rendered as result of your POST request
      $formResult =  file_get_contents (
                    $formUrl,  // page url
                    false,
                    $formContext);
      $formResult = json_decode($formResult, true);
      
      if ($formResult['error'] == false) {
          $formSuccess = 1;
      }
      else {
          $formSuccess = 0;
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

    <title>Search Members</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./searchMembers.css" rel="stylesheet">
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
              <a class="nav-link active" href="#">Search Members</a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
          <h1>Search Members</h1>

          <div class="div-createEvent">
              
            <form name="searchForm" class="form-createEvent" action="./" method="POST" onsubmit="return validateForm()">
              <h2 class="form-createEvent-heading">Select Any Fields [All Are Optional]</h2>
              
	          <input type="text" id="studentID" name="studentID" class="form-control" placeholder="Student ID">
	          <input type="text" id="firstName" name="firstName" class="form-control" placeholder="First Name">
	          <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last Name">
	      
	          Filter By Points
	          <select id="pointComparison" name="pointComparison">
	            <option value="">--SELECT--</option>
	            <option value="<">Less Than</option>
	            <option value=">">Greater Than</option>
	            <option value="=">Equal To</option>
	          </select>
	          <input type="number" id="points" name="points" class="form-control" placeholder="Points">
              <button class="btn btn-lg btn-primary btn-block" type="submit">Search Events</button>
	          **Note: If you are not getting results, try using less fields.
	          
	          <?php if ($formSuccess == 1) { ?>
	          <br></br>
	          <h2>Member Results</h2>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>StudentID</th>
                      <th>Last Name</th>
                      <th>First Name</th>
                      <th>Total Hours</th>
                      <th>Total Points</th>
                      <th>Select</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
            	        foreach($formResult['students'] as $row) {
            	            echo("<tr>");
            	            echo("<th>" . $row['StudentID'] . "</th>");
                            echo("<th>" . $row['LastName'] . "</th>");
            	            echo("<th>" . $row['FirstName'] . "</th>");
            	            echo("<th>" . $row['TotalHours'] . "</th>");
            	            echo("<th>" . $row['TotalPoints'] . "</th>");
            	            echo("<th><a href=\"http://ukyswe.com/OfficerDashboard/viewStudent?studentID=" . $row['StudentID'] . "\">View/Edit</a></th>");
            	            echo("</tr>");
            	        }
        	        ?>
        	      </tbody>
                </table>
              </div>
              <?php } else if ($formSuccess == 0) { ?>
                <br><h2>No Results Found.</h2>
                <?php } ?>
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
        function validateForm() {
            var points = document.forms["searchForm"]["points"].value;
            var pointComparison = document.forms["searchForm"]["pointComparison"].value;
            
            if (pointComparison != "" && points == "") {
                alert("Points is missing a value.");
                return false;
            }
            else if (points != "" && pointComparison == "") {
                alert("Please select a point comparison.");
                return false;
            }
            return true;
        }
    </script>
  </body>
</html>
