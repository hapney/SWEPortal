<?php
// Title: getStudentDetails.php
// Author: Sydney Norman
// Date 12/2017
//
// Retrieves the student details for a given student
//
// input:
//   studentID
// output (studentID found):
//   student details
// output (studentID not found):
//   failure message

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
            $studentDetails = $db->getStudentDetails($studentID);
            if ($studentDetails) {
                $response['error'] = false;
                $response['message'] = "Successfully Pulled the Student Details.";
                $response['studentDetails'] = $studentDetails;
            }
            else {
                $response['error'] = true;
                $response['message'] = "Could not get student details.";
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
