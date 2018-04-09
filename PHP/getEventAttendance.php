<?php
// Title: getEventAttendance.php
// Author: Sydney Norman
// Date 12/2017
//
// Retrieves the event attendance for the eventID
// 
// input:
//   eventID
// output (eventID found):
//   the event attendance
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
            $eventAttendance = $db->getEventAttendance($eventID);
            if ($eventAttendance) {
                
                $rows = array();
                foreach($eventAttendance as $row) {
                    
                    // Get Student Info
                    $studentInfo = $db->getStudentInfo($row['StudentID']);
                    if ($studentInfo) {
                        
                        // Create Array of Student and Points/Hours
	                $studentInfo['Hours'] = $row['Hours'];
	                $studentInfo['Points'] = $row['Points'];
                        
                        $rows[] = $studentInfo;
                    }
                }
                
                $response['error'] = false;
                $response['message'] = "Successfully Pulled the Event Attendance.";
                $response['eventAttendance'] = $rows;
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
