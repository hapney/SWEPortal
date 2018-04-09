<?php
// Title: getEventTypes.php
// Author: Sydney Norman
// Date 12/2017
//
// Retrieves all the event types from the database
//
// output:
//   all the event types

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    //header("Access-Control-Allow-Origin: *");

    // Create db operation object
    $db = new DbOperation();
    if (is_null($db->errMessage))
    {
        // Try to get Event Types
        $eventTypes = $db->getEventTypes();
        if ($eventTypes) {
            $response['error'] = false;
            $response['message'] = "Successfully Pulled the Event Types.";
            $response['eventTypes'] = $eventTypes;
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
else
{
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
// Echo json response
echo json_encode($response);
