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

        // delete statmenet to send to SQL server
        $stmt = $conn->prepare("DELETE FROM Contacts WHERE Name = ? AND Phone = ? AND Email = ? AND UserID = ?");
        $stmt->bind_param("sssi", $Name, $Phone, $Email, $UserID);

        // execute statement
        if($stmt->execute()) {

            // successfully removed contact
            // send back to front end
            returnWithError("");
        } else {
            echo "Contact data had no errors but SQL failed to add it to the database";
        }

        // close out statement
        $stmt->close();

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