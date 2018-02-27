<?php
// Title: getEventDetails.php
// Author: Sydney Norman
// Date 12/2017
//
// Search the user table for the emailID received from the app
// If found, put the received username and password in it.
// Send the uid in the user entry to the app
//
// input from app:
//   username, password, emailID
// output (emailID found):
//   user table entry updated with username, password,
//   active flag set true. uid sent to app with success message
// output (emailID not found):
//   failure message sent app
//
// References:
//   modified from https://www.simplifiedios.net/swift-php-mysql-tutorial/
//   by Belal Khan

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