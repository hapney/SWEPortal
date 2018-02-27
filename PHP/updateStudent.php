<?php
// Title: updateStudent.php
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