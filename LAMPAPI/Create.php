<?php
    // this is where everything related to the 
    // creation of a contact will be written

    // get data from frontend
	$inData = getRequestInfo();
    
    // variables to hold contact data
    $Name = $inData["Name"];
    $Email = $inData["Email"];
    $Phone = $inData["Phone"];
    $UserID = $inData["UserID"];

    // variables to  hold error data
    $errName = "";
    $errEmail = "";
    $errPhone = "";
    $errUserID = "";

    // connect to the server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError($conn->connect_error);
	} else {
        // check Name for errors
        if($Name == "" || !filter_var($Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]+$/")))) {
            $errName = "Error: Invalid Name";
        } else {
            // Name is ok, ready to proceed further
            echo "Name added successfully";
        }
        
        // check Email for errors
        if($Email == "") {
            $errEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to Phone
            echo "Email added successfully";
        }

        // check Phone for errors
        if($Phone == "" || !filter_var($Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z]/")))) {
            $errPhone = "Error: Invalid Phone";     
            echo $Phone;
        } else {
            // phone ok, move on
            echo "Phone added successfully ";
        }

        // check UserID for errors
        if($UserID == "" || !filter_var($UserID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]+$/")))) {
            $errUserID = "Error: Invalid UserID ";    
        } else {
            // UserID is ok
            echo "UserID added successfully ";
        }

        // now make sure name does not exist already
        $stmt = $conn->prepare("SELECT FROM Contacts WHERE Name = ? AND UserID = ?");
        $stmt->bind_param("si", $Name, $UserID);
        if($stmt->exeute()) {
            returnWithError("Contact already exists!");
        }

        // now input data into database as long as no errors
        if( ($errName == "") && ($errEmail == "") && ($errPhone == "") && ($errUserID == "")){

            // before we insert the contact into the DB, we should
            // first check to make sure they do not already exist
            
            // SQL statement to send data to database
            $stmt = $conn->prepare("INSERT INTO Contacts(Name, Phone, Email, UserID) VALUES(?, ?, ?, ?)");
            $stmt->bind_param("sssi", $Name, $Phone, $Email, $UserID);
                
            // add the contact to the database
            if($stmt->execute()){
                // contact successfully created
                // link back to front end
                returnWithError("");
            } else{
                echo "Contact data had no errors but SQL failed to add it to the database";
            }
    
            // sql call finished, go ahead and close it
            $stmt->close();

        } else {
            // data was not entered properly, so we should not send it to the database
            echo "Contact information was not entered correctly: ";
            echo $errName;
            echo $errEmail;
            echo $errPhone;
            echo $errUserID;
        }
        
        // finished comms with sql server, break communication
        $conn->close();
    }

    // helper functions for JSON commands
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>