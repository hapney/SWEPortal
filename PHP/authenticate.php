<?php
// Title: authenticate.php
// Author: Sydney Norman
// Date 12/2017
//
// Authenticates a given user
//
// input:
//   username, password
// output:
//   whether or not the user is found

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    //header("Access-Control-Allow-Origin: *");
    // See if proper parameters were provided
    if (isset($_REQUEST['username']) && isset($_REQUEST['password']))
    {
        // Get parameter values
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            // Check to see if username and password combination is a valid user
            $authenticateUser = $db->authenticateUser($username, $password);
            if ($authenticateUser) {
                $response['error'] = false;
                $response['message'] = "Successfully Logged In.";
            }
            else {
                $response['error'] = true;
                $response['message'] = "User Not Found.";
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
