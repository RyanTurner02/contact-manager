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

        // first need to find how many contacts this user has
        
        $rows = -1;
        //$stmt = conn->prepare("Select * From Contact Where UserID=?");
        //stmt->bind_param("i", $UserID);
        //if ($stmt->execute())
        //{
            // get the number of contacts this user has
            //$rows = $stmt->count();
            
            // statement to delete 
            $stmt = $conn->prepare("DELETE FROM Contacts WHERE Name = ? AND Phone = ? AND Email = ? AND UserID = ?");
            $stmt->bind_param("sssi", $Name, $Phone, $Email, $UserID);
          
            if ($stmt->execute()) {
                /*if ($stmt->count() == $rows) {
                    // the numbere of contacts has not decreased,
                    // so the cdelete failed
                    returnWithError("Delete command executed to server, but no delete occured");
                } else {
                    // delete performed, no errors
                    returnWithError("");
                }*/
                returnWithError("");
            } else {
                returnWithError("Delete command did not properly execute");
            }
        /*} else {
            returnWithError("Could not get the specified User's contacts");
        }*/

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