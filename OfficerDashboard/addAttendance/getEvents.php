<?php
    // getEvents.php
    // Author: Sydney Norman
    // This file retrieves the events with the specific eventTypeID passed in

    $eventTypeID = $_GET['eventTypeID'];
    
    // Create map with request parameters
    $params = array ('eventTypeID' => $eventTypeID);
 
    // Build Http query using params
    $query = http_build_query ($params);
    
    // Create Http context details
    $contextData = array (
        'method' => 'POST',
        'header' => "Connection: close\r\n".
                    "Content-Length: ".strlen($query)."\r\n",
                    'content'=> $query);
 
      // Create context resource for our request
      $context = stream_context_create (array ( 'http' => $contextData ));
 
      $url = "http://ukyswe.sydneynorman.com/PHP/getEvents.php";
      // Read page rendered as result of your POST request
      $eventResult =  file_get_contents (
                    $url,  // page url
                    false,
                    $context);
      $eventResult = json_decode($eventResult, true);

    $returnOptions = "";
    if ($eventResult['error'] == false) {
    foreach($eventResult['events'] as $row) {
                        $returnOptions .= "<option value='" . $row["EventID"] . "'>" . $row["Name"] . " [" . $row["Date"] . "]</option>";
                  }
    }
    else {
        $returnOptions .= "<option value=''>Error. Try again.</option>";
    }

    echo ($returnOptions);
?>
