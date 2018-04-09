<?php
// Title: searchStudents.php
// Author: Sydney Norman
// Date 12/2017
//
// Searches the database for students meeting criteria
//
// input:
//   studentID, firstName, lastName, pointComparison, points
// output:
//   studentID's meeting the criteria

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_REQUEST['studentID']) || isset($_REQUEST['firstName']) || isset($_REQUEST['lastName']) || (isset($_REQUEST['pointComparison']) && isset($_REQUEST['points'])))
    {
        // Get parameter values
        $studentID = $_REQUEST['studentID'];
        $firstName = $_REQUEST['firstName'];
        $lastName = $_REQUEST['lastName'];
        $pointComparison = $_REQUEST['pointComparison'];
        $points = $_REQUEST['points'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Try to get Event Types
            $students = $db->searchStudents($studentID, $firstName, $lastName, $pointComparison, $points);
            if ($students) {
                $response['error'] = false;
                $response['message'] = "Successfully Pulled the Students.";
                $response['students'] = $students;
            }
            else {
                $response['error'] = true;
                $response['message'] = "Could not get students.";
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
