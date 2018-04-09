<?php
// Title: updateEvent.php
// Author: Sydney Norman
// Date 12/2017
//
// Updates the event with the given eventID
//
// input from app:
//   eventID, eventName, eventDate, eventDescription, eventType, pointsPerHour, totalPoints
// output:
//   whether or not the event was updated successfully

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    // See if proper parameters were provided
    if (isset($_REQUEST['eventID']) && (isset($_REQUEST['eventName']) || isset($_REQUEST['eventDate']) || isset($_REQUEST['eventDescription']) 
            || isset($_REQUEST['eventType']) || isset($_REQUEST['pointsPerHour']) || isset($_REQUEST['totalPoints']))) {
                
        // Get parameter values
        $eventID = $_REQUEST['eventID'];
        $eventName = $_REQUEST['eventName'];
        $eventDate = $_REQUEST['eventDate'];
        $eventDescription = $_REQUEST['eventDescription'];
        $eventTypeID = $_REQUEST['eventType'];
        $pointsPerHour = $_REQUEST['pointsPerHour'];
        $totalPoints = $_REQUEST['totalPoints'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            
            $columnName = "";
            $columnValue = "";
            if ($eventName != "") {
                $columnName = "Name";
                $columnValue = $eventName;
            }
            else if ($eventDate != "") {
                $columnName = "Date";
                $columnValue = $eventDate;
            }
            else if ($eventDescription != "") {
                $columnName = "Description";
                $columnValue = $eventDescription;
            }
            else if ($pointsPerHour != "") {
                $columnName = "PointsHourly";
                $columnValue = $pointsPerHour;
            }
            else if ($totalPoints != "") {
                $columnName = "TotalPoints";
                $columnValue = $totalPoints;
            }
            else if ($eventTypeID != "") {
                $columnName = "EventTypeID";
                $columnValue = $eventTypeID;
            }
            
            if ($columnName != "") {
                $updateEvent = false;
                if ($columnName != "TotalPoints" && $columnName != "PointsHourly") {
                    $updateEvent = $db->updateEvent($eventID, $columnName, $columnValue);
                }
                else {
                    $updateEvent = $db->updatePoints($eventID, $columnName, $columnValue);
                }
                
                if ($updateEvent) {
                    $response['error'] = false;
                    $response['message'] = "Success!";
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Unable to Update Event.";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Missing Value.";
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
