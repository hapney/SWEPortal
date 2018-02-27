<?php
// Title: searchEvents.php
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
    if (isset($_REQUEST['eventType']) || isset($_REQUEST['eventName']) || isset($_REQUEST['eventDate']) || isset($_REQUEST['eventKeywords']))
    {
        // Get parameter values
        $eventType = $_REQUEST['eventType'];
        $eventName = $_REQUEST['eventName'];
        $eventDate = $_REQUEST['eventDate'];
        $eventKeywords = $_REQUEST['eventKeywords'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Try to get Event Types
            $events = $db->searchEvents($eventType, $eventName, $eventDate, $eventKeywords);
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