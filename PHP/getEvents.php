<?php
// Title: getEvents.php
// Author: Sydney Norman
// Date 12/2017
//
// Retrieves the events within a given event type
//
// input:
//   eventTypeID
// output (eventTypeID found):
//   list of events matching the event type
// output (eventTypeID not found):
//   failure message

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    header("Access-Control-Allow-Origin: *");
    if (isset($_REQUEST['eventTypeID']))
    {
        // Get parameter values
        $eventTypeID = $_REQUEST['eventTypeID'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Try to get Event Types
            $events = $db->getEvents($eventTypeID);
            if ($events) {
                $response['error'] = false;
                $response['message'] = "Successfully Pulled the Events.";
                $response['events'] = $events;
            }
            else {
                $response['error'] = true;
                $response['message'] = "Could not get events.";
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
        $response['message'] = 'Data fields are required';
    }
}
else
{
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
// Echo json response
echo json_encode($response);
