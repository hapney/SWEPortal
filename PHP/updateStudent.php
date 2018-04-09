<?php
// Title: updateStudent.php
// Author: Sydney Norman
// Date 12/2017
//
// Updates student information for a student with a given studentID
//
// input:
//   studentID, firstName, lastName
// output:
//   depending on result, success or error message

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    // See if proper parameters were provided
    if (isset($_REQUEST['studentID']) && (isset($_REQUEST['firstName']) || isset($_REQUEST['lastName']))) {
                
        // Get parameter values
        $studentID = $_REQUEST['studentID'];
        $firstName = $_REQUEST['firstName'];
        $lastName = $_REQUEST['lastName'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            
            $columnName = "";
            $columnValue = "";
            if ($firstName != "") {
                $columnName = "FirstName";
                $columnValue = $firstName;
            }
            else if ($lastName != "") {
                $columnName = "LastName";
                $columnValue = $lastName;
            }
            
            if ($columnName != "") {
                
                $updateStudent = $db->updateStudent($studentID, $columnName, $columnValue);
                if ($updateStudent) {
                    $response['error'] = false;
                    $response['message'] = "Success!";
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Unable to Update Student.";
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
