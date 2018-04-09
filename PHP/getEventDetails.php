<?php
// Title: getEventDetails.php
// Author: Sydney Norman
// Date 12/2017
//
// Retrieves the details for a given event
//
// input:
//   eventID
// output (eventID found):
//   event details
// output (eventID not found):
//   failure message

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_REQUEST['eventID'])) {
        
        $eventID = $_REQUEST['eventID'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Try to get Event Types
            $eventDetails = $db->getEventDetails($eventID);
            if ($eventDetails) {
                $response['error'] = false;
                $response['message'] = "Successfully Pulled the Event Types.";
                $response['eventDetails'] = $eventDetails;
            }
            else {
                $response['error'] = true;
                $response['message'] = "Could not get event types.";
            }
        }
        else
        {
            $response['error'] = true;
            $response['message'] = $db->errMessage;
        }
    }
    else {
        $response['error'] = true;
        $response['message'] = "Missing EventID.";
    }
}
else
{
    $response['error'] = true;
    $response['message'] = 'Invalid request.';
}
// Echo json response
echo json_encode($response);
