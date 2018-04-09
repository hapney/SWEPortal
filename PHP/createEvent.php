<?php
// Title: createEvent.php
// Author: Sydney Norman
// Date 12/2017
//
// Creates a new event with the data provided
//
// input:
//   name, date, description, pointsHourly, totalPoints, eventTypeID
// output:
//   whether or not the event was successfully created

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    //header("Access-Control-Allow-Origin: *");
    // See if proper parameters were provided
    if (isset($_REQUEST['name']) && isset($_REQUEST['date']) && isset($_REQUEST['description'])
            && isset($_REQUEST['pointsHourly']) && isset($_REQUEST['totalPoints']) && isset($_REQUEST['eventTypeID']))
    {
        // Get parameter values
        $name = $_REQUEST['name'];
        $date = $_REQUEST['date'];
        $description = $_REQUEST['description'];
        $pointsHourly = $_REQUEST['pointsHourly'];
        $totalPoints = $_REQUEST['totalPoints'];
        $eventTypeID = $_REQUEST['eventTypeID'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Check to see if event type already exists
            $doesEventExist = $db->doesEventExist($name);
            if (!$doesEventExist) {
                
                $createEvent = $db->createEvent($name, $date, $description, $pointsHourly, $totalPoints, $eventTypeID);
                if ($createEvent) {
                    $response['error'] = false;
                    $response['message'] = "Successfully Created Event.";
                    $response['eventID'] = $createEvent;
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Unable to Create Event.";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Event Already Exists.";
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
