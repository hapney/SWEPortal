<?php
// Title: Funcs
// Author: Joshua Cockerill
//
// This file contains important functions (not regarding database operations)
// used with the Luna app's web api
//
// References:
//   modified from https://www.simplifiedios.net/swift-php-mysql-tutorial/
//   by Belal Khan

/**
 * Validates request parameters exist (not including whitespace)
 * @param $required_fields
 *   an array of strings of required request parameters
 * @return bool containing whether all parameters exist in the
 *   request and do not contain only whitespace chars
 */
function verifyRequiredParams($required_fields)
{
    // Get the request parameters
    $request_params = $_REQUEST;
    // Loop through all parameters
    foreach ($required_fields as $field)
    {
        // Check if any required parameter is missing or contains just whitespace
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0)
        {
            // Return that required parameters were not verified
            return false;
        }
    }
    return true;
}