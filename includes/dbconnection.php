<?php
// Title: DbConnection
// Author: Sydney Norman
//
// This class contains a function for connecting to a MySQL database
// using parameters specified in constants.php
//
// References:
//   modified from https://www.simplifiedios.net/swift-php-mysql-tutorial/
//   by Belal Khan

class DbConnection
{
    private $conn;
 
    /**
     * Establish mysqli database connection
     * @param none
     * @return array containing error conditions and an
     *   error message or database connection handler
     */
    function connect()
    {
        require_once 'constants.php';

        $response = [];
        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
         
        // Check for database connection error
        if (mysqli_connect_errno()) 
	    {
            $response['error'] = true;
            $response['message'] = "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        else
        { 
            // No error so include connection in response array
            $response['error'] = false;
            $response['message'] = $this->conn;
        }  
        
        //Return response
        return $response;
    }
}
