<?php
// Title: addAttendance.php
// Author: Sydney Norman
// Date 12/2017
//
// Add the attendance list to the eventID in the database
// 
// input:
//   eventType, event, col1, col2, col3, col4, attendanceText
// output:
//   a list of students successfully created,
//   a list of students not successfully created
//   a list of students successfully added to event attendance
//   a list of students not successfully added to event attendance

// Importing required scripts
require_once '../includes/dboperation.php';
require_once '../includes/funcs.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET')
{
    //header("Access-Control-Allow-Origin: *");
    // See if proper parameters were provided
    if (isset($_POST['eventType']) && isset($_POST['event']) && isset($_POST['col1']) && isset($_POST['col2'])
        && isset($_POST['col3']) && isset($_POST['col4']) && isset($_POST['attendanceText']))
    {
        // Get parameter values
        $eventType = $_REQUEST['eventType'];
        $eventID = $_REQUEST['event'];
        $col1 = $_REQUEST['col1'];
        $col2 = $_REQUEST['col2'];
        $col3 = $_REQUEST['col3'];
        $col4 = $_REQUEST['col4'];
        $attendanceText = $_REQUEST['attendanceText'];
        
        // Create a columns array
	    $columns = array($col1, $col2, $col3, $col4);
	    
	    // Find the index of each column description in the array
	    $hoursIdx = -1;
	    //$emailIdx = -1;
	    for ($i = 0; $i < 5; $i++) {
	      if ($columns[$i] == "StudentID") {
	    	$studentIDIdx = $i;
	      }
	      else if ($columns[$i] == "FirstName") {
	    	$firstNameIdx = $i;
	      }
	      else if ($columns[$i] == "LastName") {
	    	$lastNameIdx = $i;
	      }
	      else if ($columns[$i] == "Hours") {
	    	$hoursIdx = $i;
	      }
	    }
        
        // Create db operation object
        $db = new DbOperation();
        if (is_null($db->errMessage))
        {
            
            // Get the number of points/points per hour
            $getPoints = $db->getPoints($eventID);
            
            if ($getPoints) {
                $totalPoints = $getPoints['TotalPoints'];
                $pointsHourly = $getPoints['PointsHourly'];
                
                if (($totalPoints > 0 && $hoursIdx == -1) || ($pointsHourly > 0 && $hoursIdx != -1)) {
                    
            	    // Create Arrays for Results
                    $createStudentSuccess = array();
            	    $createStudentFailure = array();
            	    $addAttendanceSuccess = array();
            	    $addAttendanceFailure = array();
            	    
                    $txt = "" . $col1 . "\t" . $col2 . "\t" . $col3 . "\n";
                    
                    // Loop through each line in the text
                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $attendanceText) as $line)
                    {
                        $parts = preg_split('/\s+/', $line);
                        
                        // Calculate Hours
            		    $hours = -1;
            		    if ($hoursIdx != -1) {
            		        $hours = $parts[$hoursIdx];
            		    }
            			        
            		    // Calculate Points
            		    $points = -1;
            		    if ($totalPoints != 0) {
            		        $points = $totalPoints;
            		    }
            		    else if ($pointsHourly != 0) {
            		        $points = $hours * $pointsHourly;
            		    }
                        
                        $studentID = $parts[$studentIDIdx];
            		    $firstName = $parts[$firstNameIdx];
            		    $lastName = $parts[$lastNameIdx];
            		    $studentLine = "[" . $studentID . "] " . $lastName . ", " . $firstName;
            
                        // Check if Student Exists
            		    $doesStudentExist = $db->doesStudentExist($parts[$studentIDIdx]);
                        if (!$doesStudentExist) {
            
                            $createStudent = $db->createStudent($studentID, $firstName, $lastName);
                            if ($createStudent) {
            			        
            			        $createStudentSuccess[] = $studentLine;
            			        
            			        // Add the Attendance
            			        $addAttendance = $db->addAttendance($eventID, $parts[$studentIDIdx], $hours, $points);
            			        if ($addAttendance) {
            				        $addAttendanceSuccess[] = $studentLine;
            			        }
            			        else {
            				        $addAttendanceFailure[] = $studentLine;
            			        }
            			       
                        	}
            		        else {
            			        // Failure to Create Student
            			        $createStudentFailure[] = $studentLine;
            		        }
            		    }
            		    else {
            		        
            		        // Add the Attendance
            			    $addAttendance = $db->addAttendance($eventID, $parts[$studentIDIdx], $hours, $points);
            			    if ($addAttendance) {
            				    $addAttendanceSuccess[] = $studentLine;
            			    }
            			    else {
            				    $addAttendanceFailure[] = $studentLine;
            			    }
            		    }
            	    } 
            
            	    $response['error'] = false;	
            	    $response['message'] = $txt;
            	    $response['createStudentSuccess'] = $createStudentSuccess;
            	    $response['createStudentFailure'] = $createStudentFailure;
            	    $response['addAttendanceSuccess'] = $addAttendanceSuccess;
            	    $response['addAttendanceFailure'] = $addAttendanceFailure;
                }
                else {
                    $response['error'] = true;
                    
                    if ($totalPoints > 0) {
                        $response['message'] = "Event uses total points. No hours allowed.";
                    }
                    else {
                        $response['message'] = "Event uses points per hour. Hours must be provided.";
                    }
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Error Getting Points.";
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
