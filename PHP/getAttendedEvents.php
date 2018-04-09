<?php
// Title: getEventAttendance.php
// Author: Sydney Norman
// Date 12/2017
//
// Retrieves the event attendance for a given studentID
//
// input:
//   studentID
// output:
//   attended events

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_REQUEST['studentID'])) {
        
        $studentID = $_REQUEST['studentID'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Try to get Event Types
            $attendedEvents = $db->getAttendedEvents($studentID);
            $response['message'] = $attendedEvents;
            if ($attendedEvents) {
                
                $response['error'] = false;
                $response['message'] = "Successfully Pulled the Attended Events.";
                $response['attendedEvents'] = $attendedEvents;
            }
            else {
                $response['error'] = true;
                $response['message'] = "Could not get attended events.";
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
        $response['message'] = "Missing StudentID.";
    }
}
else
{
    $response['error'] = true;
    $response['message'] = 'Invalid request.';
}
// Echo json response
echo json_encode($response);
