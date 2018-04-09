<?php
// Title: createEventType.php
// Author: Sydney Norman
// Date 12/2017
//
// Creates a new event type from given information
//
// input:
//   name, description
// output:
//   whether or not the event type was created successfully

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    //header("Access-Control-Allow-Origin: *");
    // See if proper parameters were provided
    if (isset($_REQUEST['name']) && isset($_REQUEST['description']))
    {
        // Get parameter values
        $name = $_REQUEST['name'];
        $description = $_REQUEST['description'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Check to see if event type already exists
            $doesEventTypeExist = $db->doesEventTypeExist($name);
            if (!$doesEventTypeExist) {
                
                $createEventType = $db->createEventType($name, $description);
                if ($createEventType) {
                    $response['error'] = false;
                    $response['message'] = "Successfully Created Event.";
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Unable to Create Event.";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Event Type Already Exists.";
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
        $response['message'] = 'Username and password are required';
    }
}
else
{
    $response['error'] = true;
    $response['message'] = 'Invalid request';
}
// Echo json response
echo json_encode($response);
