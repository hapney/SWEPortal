<?php
// Title: createEvent.php
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