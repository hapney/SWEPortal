<?php
// Title: DbOperation.php
// Author: Sydney Norman
//
// This class contains specific functions for querying data in
// the MySQL database connected to via dbconnection.php and constants.php
// for the SWEPortal web api (handles SELECT, INSERT, UPDATE operations)
//
// References:
//   modified from https://www.simplifiedios.net/swift-php-mysql-tutorial/
//   by Belal Khan

class DbOperation
{
    public $errMessage = null;  //message if error occurs connecting to database
    private $conn = null;       //database connection if no error occurs
    /**
     * Constructor creates and connects with DbConnection
     * @param none
     * @return self
     */
    function __construct()
    {
        require_once dirname(__FILE__) . '/constants.php';
        require_once dirname(__FILE__) . '/dbconnection.php';
        // Try opening db connection
        $db = new DbConnection();
        $result = $db->connect();
        // Check for errors
        if ($result['error'])
        {
            $this->errMessage = $result['message'];
        }
        else
        {
            $this->conn = $result['message'];
        }
    }
    
    /**
     * Function to authenticate username/password on login
     * @param $username string of user to be authenticated
     * @param $pass string of user to be authenticated
     * @return bool whether or not username/password combination
     *   was found in the database
     */
    public function authenticateUser($username, $pass)
    {
        $stmt = $this->conn->prepare('SELECT OfficerID FROM Officer WHERE username = ? AND password = ?');
        $stmt->bind_param('ss', $username, $pass);
        $stmt->execute();
        $stmt->store_result();
        // Return if results were found
        return $stmt->num_rows > 0;
    }
    
    /**
     * Function to create new user with a random emailID 
     * that is returned and sent to the new user in her
     * supplied email address
     * A new user row is created in the user table that 
     * has the emailID, and a random uid
     * @param no passed parameter required
     * @return $emailID: random ID sent to new user's email address
     *   when user successfully inserted in the database
     *   null if error
     */
    public function createUser()
    {
        //  create random emailID
        // although high probability that random function will
        // create a unique ID, it may not happen so:
        // check if created emailID in table, try 1000 times
        // to create a random emailD (if fails after 1000 tries,
        // must be randomizing bug). Terminating loop after 1000
        // times prevents endless loop when there is a bug
        $count = 0;     // Set loop count = 0
        $emailID = $this->genRandomString();
        // while emailID not unique && < 1000 tries
        while ($this->doesEmailIDExist($emailID) && $count < 1000) {
            $emailID = $this->genRandomString();
            $count++;   // increment count
        }   // end random ID while loop   
        if ($count < 1000)   // If unique emailID created
        {
            // Create random uid
            $uid = $this->createRandomUid();
            if ($uid > 0)   // no error created uid
            {
                $stmt = $this->conn->prepare('INSERT INTO User (emailID,uid) VALUES (?,?)');
                $stmt->bind_param('ss', $emailID,$uid);
                if ($stmt->execute())
                {
                    //User created successfully
                    return $emailID;
                }
                else
                {
                    //Error creating user
                    return null;
                }
            } else {    // Error creating user
                return null;
            }
        }
        else
        {
            // Error creating user no unique emailID
            return null;
        }
    }
    
    /**
     * Function to determine if a username already exists
     * @param $username string of name to be checked
     * @return bool whether or not username exists in the database
     *   regardless of activity status
     *   NOTE: keeps usernames unique
     */
    public function doesUserExist($username)
    {
        $stmt = $this->conn->prepare('SELECT OfficerID FROM User WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        // Return if results were found
        return $stmt->num_rows > 0;
    }
    
    // BEING USED
    /**
     * Function to update Event details
     * @param $eventID: event ID
     * @param $columnName: column to update
     * @param $columnValue: value to update column with
     * @return bool whether or not update was successful
     */
    public function updateEvent($eventID, $columnName, $columnValue) {
        
        if ($columnName == "Date") {
            $columnValue = date('Y-m-d', strtotime(str_replace('-', '/', $columnValue)));
        }
        
        $stmt = $this->conn->prepare("UPDATE SWEEvent SET " . $columnName . " = '" . $columnValue . "' WHERE EventID = " . $eventID);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } // end of updateEvent

    // BEING USED
    /**
     * Function to update Student details
     * @param $studentID: student ID
     * @param $columnName: column to update
     * @param $columnValue: value to update column with
     * @return bool whether or not update was successful
     */
    public function updateStudent($studentID, $columnName, $columnValue) {
        
        $stmt = $this->conn->prepare("UPDATE Student SET " . $columnName . " = '" . $columnValue . "' WHERE StudentID = " . $studentID);
        
        if ($stmt->execute()) {
            return true;
        }
        return false; 
    } // end of updateStudent
    
    // BEING USED
    /**
     * Function to update Event point details
     * @param $eventID: event ID
     * @param $columnName: column to update
     * @param $columnValue: value to update column with
     * @return bool whether or not update was successful
     */
    public function updatePoints($eventID, $columnName, $columnValue) {
        
        // Error Checking
        if ($columnName != "PointsHourly" && $columnName != "TotalPoints") {
            return false;
        }
        
        if ($columnName == "PointsHourly") {
            $correspondingName = "TotalPoints";
            $queryStr = "UPDATE Attendance SET Points = (Attendance.Hours * " . $columnValue . ") WHERE EventID = " . $eventID;
        }
        else {
            $correspondingName = "PointsHourly";
            $queryStr = "UPDATE Attendance SET Points = " . $columnValue . " WHERE EventID = " . $eventID;
        }
        
        $stmt = $this->conn->prepare("UPDATE SWEEvent SET " . $columnName . " = '" . $columnValue . "', " . $correspondingName . " = '0' WHERE EventID = " . $eventID);
        
        if ($stmt->execute()) {
            $stmt2 = $this->conn->prepare($queryStr);
            if ($stmt2->execute()) {
                return "true";
            }
        }
        return "false"; 
    } // end of updatePoints
    
    // BEING USED
    /**
     * Function to determine if an event type already exists
     * @param $name string of event type name to be checked
     * @return bool whether or not event type exists in the database
     *   NOTE: keeps event types unique
     */
    public function doesEventTypeExist($name)
    {
        $stmt = $this->conn->prepare('SELECT EventTypeID FROM EventType WHERE Name = ?');
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        // Return if results were found
        return $stmt->num_rows > 0;
    } // end of doesEventTypeExist
    
    // BEING USED
    /**
     * Function to determine if an event already exists
     * @param $name string of event name to be checked
     * @return bool whether or not event exists in the database
     *   NOTE: keeps events unique
     */
    public function doesEventExist($name)
    {
        $stmt = $this->conn->prepare('SELECT EventID FROM SWEEvent WHERE Name = ?');
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        // Return if results were found
        return $stmt->num_rows > 0;
    } // end of doesEventExist
    
    /**
     * Function to generate random string of specified length
     * @param $length int number of chars of generated string
     * @return string of random chars using mt_rand and numbers,
     *   lowercase letters, and uppercase letters
     */
    private function genRandomString($length = 8)
    {
        $str = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($chars) - 1;
        // Generate string with length number of chars
        for ($i = 0; $i < $length; ++$i)
        {
            // Get new random char and add to end of string
            $rand = mt_rand(0, $max);
            $str .= $chars[$rand];
        }
        // Return random string
        return $str;
    }
   
    // BEING USED
    /**
     * Function to authenticate OfficerID exists in user table
     * $officerID: OfficerID to be authenticated
     * returns: true (1): officerID exists
     *          false (0): officerID does not exist
     */
    public function authenticateOfficerID($officerID)
    {       
        //does active User ID exist
        $stmt = $this->conn->prepare('SELECT OfficerID FROM Officer WHERE OfficerID = ?');
        $stmt->bind_param('s', $officerID);
        $stmt->execute();
        $stmt->store_result();
        // Return if results were found
        return $stmt->num_rows > 0;
    }   // end of authenticateOfficerID
    
   /**
     * Function to create random officerID. Search user table uids
     * to ensure that it is not a duplicate
     * returns: random officerID (0 if error)
     */
    public function createRandomOfficerID()
    {       
        // create random officerID
        // although high probability that random function will
        // create a unique ID, it may not happen so:
        // check if created random ID in table, try 1000 times
        // to create a random ID (if fails after 1000 tries,
        // must be randomizing bug). Terminating loop after 1000
        // times prevents endless loop when there is a bug
        // random officerID starts at 11. 1-10 reserved for testing
        $count = 0;     // Set $count = 0
        $max = 100000;  // maximum random number
        $uid = mt_rand(11,$max); // get random number from 1 $max
        // while officerID not unique && < 1000 tries
        while ($this->doesOfficerIDExist($officerID) && $count < 1000) {
            $officerID = mt_rand(11,$max); 
            $count++;   // increment count
        }   // end random ID while loop  
        // if random uid created count < 1000
        if ($count < 1000)
        {
            return $officerID;
        } 
        else {
            return 0;
        }
    }   // end  createRandomOfficerID

    // BEING USED
    /**
     * doesOfficerIDExist
     * Function to determine if an OfficerID already exists
     * @param $officerID: OfficerID to check 
     * @return  true (1) when OfficerID exists in user table
     *         false (0) OfficerID not in user table         
     *   NOTE: keeps OfficerIDs unique
     */
    public function doesOfficerIDExist($officerID)
    {
        $stmt = $this->conn->prepare('SELECT OfficerID FROM Officer WHERE OfficerID = ?');
        $stmt->bind_param('s', $officerID);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }   // end doesOfficerIDExist
    
    // BEING USED
    /**
     * doesStudentExist
     * Function to determine if a student already exists
     * @param $studentID: StudentID to check 
     * @return  true (1) when StudentID exists in user table
     *         false (0) StudentID not in user table    
     */
    public function doesStudentExist($studentID)
    {
        $stmt = $this->conn->prepare('SELECT StudentID FROM Student WHERE StudentID = ?');
        $stmt->bind_param('s', $studentID);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }   // end doesStudentExist
    
    /**
     * Function to create a new daily question Entry in entry table
     *  $uid:  user ID
     *  $date: date incident occurred
     *  daily question data for all daily questions 
     * @return true: new row in entry table with daily question data
     *.        false: error creating new row in entry table
     */
    public function createEntry($uid, $entry_date, $onPeriod, $sexualInterest, $sexualAttitude, $sexualArousal, $kissing, $caressing, $fondling, $masturbation, $oral, $anal, $vaginal, $none, $other, $intensity)
    {
        $date = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
        $stmt = $this->conn->prepare('INSERT INTO Entry (uid, entry_date, onPeriod, sexualInterest, sexualAttitude, sexualArousal, kissing, caressing, fondling, masturbation, oral, anal, vaginal, none, other, intensity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssssssssssssss', $uid, $entry_date, $onPeriod, $sexualInterest, $sexualAttitude, $sexualArousal, intval($kissing), intval($caressing), intval($fondling), intval($masturbation), intval($oral), intval($anal), intval($vaginal), intval($none), $other, $intensity);
        if ($stmt->execute())
        {
            //Entry created successfully
            return true;
        }
        else
        {
            //Error creating Entry
            return false;
        }
    }   // end of createEntry
    
    // BEING USED
    /**
     * Function to create a new event type in EventType table
     *  $name:  event type name
     *  $description: event type description 
     * @return true: new row in EventType table
     *.        false: error creating new row in EventType table
     */
    public function createEventType($name, $description)
    {
        $stmt = $this->conn->prepare('INSERT INTO EventType (Name, Description) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $description);
        if ($stmt->execute())
        {
            //Entry created successfully
            return true;
        }
        else
        {
            //Error creating Entry
            return false;
        }
    }   // end of createEventType
    
    // BEING USED
    /**
     * Function to create a new event in SWEEvent table
     *  $name:  event name
     *  $date: event date
     *  $description: event type description
     *  $pointsHourly
     *  $totalPoints
     *  $eventTypeID
     * @return EventID: new row eventID from SWEEvent table
     *.        false: error creating new row in SWEEvent table
     */
    public function createEvent($name, $date, $description, $pointsHourly, $totalPoints, $eventTypeID)
    {
        $date = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
        
        $stmt = $this->conn->prepare('INSERT INTO SWEEvent (Name, Date, Description, PointsHourly, TotalPoints, EventTypeID) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $name, $date, $description, $pointsHourly, $totalPoints, $eventTypeID);
        if ($stmt->execute())
        {
            // Return Event ID
	        $sql = "SELECT EventID FROM SWEEvent WHERE Name = '" . $name . "' AND Date = '" . $date . "'";
	        $result = $this->conn->query($sql);
	        // this should only ever return 1 record (for a user)
	        if ($result->num_rows == 1) {
	            $row = $result->fetch_assoc();
	            return $row["EventID"];
	        }
	        else {
	            return false;
	        }
        }
        else
        {
            //Error creating Entry
            return false;
        }
    }   // end of createEvent
    
    // BEING USED
    /**
     * Function to create a new attendance in Attendance table
     *  $eventID:  event ID
     *  $studentID: student ID
     *  $hours: hours spent
     *  $points: total points
     * @return true: new row in Attendance table
     *.        false: error creating new row in Attendance table
     */
    public function addAttendance($eventID, $studentID, $hours, $points)
    {
        
        $stmt = $this->conn->prepare('INSERT INTO Attendance (EventID, StudentID, Hours, Points) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $eventID, $studentID, $hours, $points);
        if ($stmt->execute())
        {
            // Attendance created successfully
            return true;
        }
        else
        {
            // Error creating Attendance
            return false;
        }
    }   // end of addAttendance
    
    // BEING USED
    /**
     * Function to create a new student in Student table
     *  $studentID: student ID
     *  $firstName: hours spent
     *  $lastName: total points
     * @return true: new row in Student table
     *.        false: error creating new row in Student table
     */
    public function createStudent($studentID, $firstName, $lastName)
    {
        
        $stmt = $this->conn->prepare('INSERT INTO Student (StudentID, FirstName, LastName) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $studentID, $firstName, $lastName);
        if ($stmt->execute())
        {
            // Student created successfully
            return true;
        }
        else
        {
            // Error creating Student
            return false;
        }
    }   // end of createStudent
    
    /**
     * Function to create a new selfie entry
     *  eid:  Entry ID
     *  selfie data
     * @return true: new row in selfie table with selfie questions data
     *.        false: error creating new row in selfie table
     */
    public function createSelfieEntry($eid, $feelingsPassTime, $feelingsRecordInterest, $feelingsAttention, $feelingsCommunication, $descriptions)
    {
        $stmt = $this->conn->prepare('INSERT INTO Selfie (eid, feelingsPassTime, feelingsRecordInterest, feelingsAttention, feelingsCommunication, descriptions) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $eid, $feelingsPassTime, $feelingsRecordInterest, $feelingsAttention, $feelingsCommunication, $descriptions);
        if ($stmt->execute())
        {
            // Selfie created successfully
            return true;
        }
        else
        {
            // Error creating Selfie
            return false;
        }
    }   // end of createSelfieEntry
    
    // BEING USED
    /**
    * Function to search Events
    * @return events: all corresponding events
    */
    public function searchStudents($studentID, $firstName, $lastName, $pointComparison, $points) {
	    $sql = "SELECT Student.*, SUM(Attendance.Hours) AS TotalHours, SUM(Attendance.Points) AS TotalPoints FROM Student, Attendance WHERE Attendance.StudentID = Student.StudentID";
		    
		if ($studentID != "") {
		    $sql .= " AND Student.StudentID = '" . $studentID . "'";
		}
		if ($firstName != "") {
		    $sql .= " AND Student.FirstName LIKE '%" . $firstName . "%'";
		    $constraintAdded = true;
		}
		if ($lastName != "") {
		    $sql .= " AND Student.LastName LIKE '%" . $lastName . "%'";
		    $constraintAdded = true;
		}
		$sql .= " GROUP BY Attendance.StudentID";
		
		if ($pointComparison != "") {
		    $sql .= " Having TotalPoints " . $pointComparison . " " . $points;
		}
		$sql .= " ORDER BY TotalPoints";
		
		$result = $this->conn->query($sql);
	    
	    $rows = array();
	    
	    for ($r = 0; $r < $result->num_rows; $r++) {
	        $row = $result->fetch_array(MYSQLI_ASSOC);
	        $rows[] = $row;
	    }
	    return $rows; 
    } // end of searchStudents
    
    // BEING USED
    /**
    * Function to search Events
    * @return events: all corresponding events
    */
    public function searchEvents($eventTypeID, $eventName, $eventDate, $eventKeywords) {
	    $sql = "SELECT EventID, Name, Date, EventTypeID, Description
		    FROM SWEEvent";
		    
		$constraintAdded = false;
		if ($eventTypeID != "" || $eventName != "" || $eventDate != "" || $eventKeywords != "") {
		    $sql .= " WHERE";
		}
		if ($eventTypeID != "") {
		    $sql .= " EventTypeID = '" . $eventTypeID . "'";
		    $constraintAdded = true;
		}
		if ($eventName != "") {
		    if ($constraintAdded) {
		        $sql .= " AND";
		    }
		    $sql .= " Name LIKE '%" . $eventName . "%'";
		    $constraintAdded = true;
		}
		if ($eventDate != "") {
		    if ($constraintAdded) {
		        $sql .= " AND";
		    }
		    $eventDate = date('Y-m-d', strtotime(str_replace('-', '/', $eventDate)));
		    $sql .= " Date = '" . $eventDate . "'";
		    $constraintAdded = true;
		}
		if ($eventKeywords) {
		    if ($constraintAdded) {
		        $sql .= " AND";
		    }
		    $sql .= " (Name LIKE '%" . $eventKeywords . "%' OR Description LIKE '%" . $eventKeywords . "%')";
		    $constraintAdded = true;
		}
		$sql .= " ORDER BY EventTypeID, Date";
		    
	    $result = $this->conn->query($sql);
	    
	    $rows = array();
	    
	    for ($r = 0; $r < $result->num_rows; $r++) {
	        $row = $result->fetch_array(MYSQLI_ASSOC);
	        $rows[] = $row;
	    }
	    return $rows;
    } // end of searchEvents
    
    // BEING USED
    /**
    * Function to get all Event Types
    * @return eventTypes: all event types
    */
    public function getEventTypes() {
	    $sql = "SELECT EventTypeID, Name
		    FROM EventType";
	    $result = $this->conn->query($sql);
	    
	    $rows = array();
	    
	    for ($r = 0; $r < $result->num_rows; $r++) {
	        $row = $result->fetch_array(MYSQLI_ASSOC);
	        $rows[] = $row;
	    }
	    return $rows;
    } // end of getEventTypes
    
    // BEING USED
    /**
    * Function to get all event details
    *   $eventID: the event's id
    * @return eventDetails: all event details
    */
    public function getEventDetails($eventID) {
	    $sql = "SELECT *
		    FROM SWEEvent WHERE EventID = '" . $eventID . "'";
	    $result = $this->conn->query($sql);
	    
	    $row = $result->fetch_array(MYSQLI_ASSOC);
	    return $row;
    } // end of getEventDetails
    
    // BEING USED
    /**
    * Function to get all student details
    *   $studentID: the student's id
    * @return studentDetails: all student details
    */
    public function getStudentDetails($studentID) {
	    $sql = "SELECT Student.*, SUM(Attendance.Points) AS TotalPoints, (SELECT SUM(A.Hours) 
         FROM Attendance AS A, Student AS S 
         WHERE A.StudentID = S.StudentID 
         	AND A.StudentID = " . $studentID . " 
         	AND A.Hours > -1
         GROUP BY A.StudentID) AS TotalHours 
	    FROM Student, Attendance 
	    WHERE Attendance.StudentID = Student.StudentID AND Attendance.StudentID = " . $studentID . " 
	    GROUP BY Attendance.StudentID";
	    $result = $this->conn->query($sql);
	    
	    $row = $result->fetch_array(MYSQLI_ASSOC);
	    return $row;
    } // end of getStudentDetails
    
    // BEING USED
    /**
    * Function to get all student details
    *   $eventID: the student's id
    * @return studentDetails: all student details
    */
    public function getStudentInfo($studentID) {
	    $sql = "SELECT *
		    FROM Student WHERE StudentID = '" . $studentID . "'";
	    $result = $this->conn->query($sql);
	    
	    $row = $result->fetch_array(MYSQLI_ASSOC);
	    return $row;
    } // end of getStudentInfo
    
    // BEING USED
    /**
    * Function to get all event attendance
    *   $eventID: the event's id
    * @return eventAttendance: all event attendance
    */
    public function getEventAttendance($eventID) {
	    $sql = "SELECT *
		    FROM Attendance WHERE EventID = '" . $eventID . "'";
	    $result = $this->conn->query($sql);
	    
	    $rows = array();
	    for ($r = 0; $r < $result->num_rows; $r++) {
	        $row = $result->fetch_array(MYSQLI_ASSOC);
	        
	        $rows[] = $row;
	    }
	    return $rows;
    } // end of getEventAttendance
    
    // BEING USED
    /**
    * Function to get all attended events
    *   $eventID: the students's id
    * @return attendedEvents: all events attended
    */
    public function getAttendedEvents($studentID) {
	    $sql = "SELECT Attendance.*, SWEEvent.*, EventType.Name AS EventTypeName
                FROM Attendance, SWEEvent, EventType
                WHERE Attendance.EventID = SWEEvent.EventID
                AND Attendance.StudentID = " . $studentID . " 
                AND EventType.EventTypeID = SWEEvent.EventTypeID
                ORDER BY SWEEvent.EventTypeID";
                
                //return $sql;
	    $result = $this->conn->query($sql);
	    
	    $rows = array();
	    for ($r = 0; $r < $result->num_rows; $r++) {
	        $row = $result->fetch_array(MYSQLI_ASSOC);
	        
	        $rows[] = $row;
	    }
	    return $rows; 
    } // end of getAttendedEvents
    
    // BEING USED
    /**
    * Function to get both Total Points and Points Hourly
    *   $eventID: the event's id
    * @return Total Points and PointsHourly
    */
    public function getPoints($eventID) {
	    $sql = "SELECT PointsHourly, TotalPoints
		    FROM SWEEvent WHERE EventID = '" . $eventID . "'";
	    $result = $this->conn->query($sql);
	    
	    if ($result->num_rows == 1) {
	       return $result->fetch_array(MYSQLI_ASSOC);
	    }
	    else {
	       return -1.0;
	    }
    } // end of getPoints
    
    // BEING USED
    /**
    * Function to get all SWEEvents
    * @return events: all events
    */
    public function getEvents($eventTypeID) {
	    $sql = "SELECT EventID, Name, Date, EventTypeID
		    FROM SWEEvent WHERE EventTypeID = '" . $eventTypeID . "'";
	    $result = $this->conn->query($sql);
	    
	    $rows = array();
	    
	    for ($r = 0; $r < $result->num_rows; $r++) {
	        $row = $result->fetch_array(MYSQLI_ASSOC);
	        $rows[] = $row;
	    }
	    return $rows;
    } // end of getEvents
    
    /**
    * Function to get OfficerID
    *  $username: username
    *  $password: password
    * @return uid: the user's OfficerID
    *    if -1.0, error: user has no OfficerID
    */
    public function getOfficerID($username, $password) {
	    $sql = "SELECT OfficerID
		    FROM Officer WHERE username = '" . $username . "' AND password = '" . $password . "'";
	    $result = $this->conn->query($sql);
	    // this should only ever return 1 record (for a user)
	    if ($result->num_rows == 1) {
	       $row = $result->fetch_assoc();
	       return $row["OfficerID"];
	    }
	    else {
	       return -1.0;
	    }
    } // end of getOfficerID
    
}  // end of DbOperation class
