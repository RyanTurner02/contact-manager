<?php
    // this file is where the delete
    // feature will be implemented

    // get data from frontend
	$inData = getRequestInfo();
    
    // variables to hold contact data
    $Name = $inData["Name"];
    $Email = $inData["Email"];
    $Phone = $inData["Phone"];
    $UserID = $inData["UserID"];

    // connect to the server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError($conn->connect_error);
	} else {
         // check Name for errors
         if(($Name == "") || (!filter_var($Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))) {
            $errName = "Error: Invalid Name";
        } else {
            // Name is ok, ready to proceed further
            echo "Name found successfully";
        }
        
        // check Email for errors
        if($Email == ""){
            $errEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to Phone
            echo "Email found successfully";
        }
        
        // check Phone for errors
        if($Phone = "" || !filter_var($Phone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errPhone = "Error: Invalid Phone";     
        } else {
            // Phone is ok
            echo "Phone found successfully";
        }

        // check UserID for errors
        if($UserID = "" || !filter_var($UserID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errPhone = "Error: Invalid UserID";     
        } else {
            // UserID is ok
            echo "UserID found successfully";
        }

        //if data is ok, delete it
        if( ($errName == "") && ($errEmail == "") && ($errPhone == "") && ($errUserID == "")) {

            // delete statmenet to send to SQL server
            $stmt = "DELETE FROM Contacts WHERE Name = ? AND Phone = ? AND Email = ? AND UserID = ?";
            $stmt->bind_param("sssi", $Name, $Email, $Phone, $UserID);

            // execute statement
            if($stmt->execute()) {

                // successfully removed contact
                
                // html page to redirect to
                header("location: index.html");
                exit();
            } else {
                echo "Contact data had no errors but SQL failed to add it to the database";
            }

            // close out statement
            $stmt->close();
        } else {
            // data was not entered properly, so we should not send it to the database
            echo "Contact information was not entered correctly: ";
            echo $errName;
            echo $errEmail;
            echo $errPhone;
            echo $errUserID;
        }

        // close connection
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