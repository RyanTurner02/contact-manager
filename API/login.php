
<?php
	// this fille contains everything
	// related to the login and the
	// signup 

	$inData = getRequestInfo();
	
	// variables to hold login info
	$id = 0;
	$firstName = "";
	$lastName = "";

	// connect to the server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		// prepare statement for login to the database
		$stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $inData["login"], $inData["password"]);

		// attempt login
		$stmt->execute();
		$result = $stmt->get_result();

		// check if login was successful
		if( $row = $result->fetch_assoc()  )
		{
			// login was successful, return login information
			returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'] );
		}
		else
		{
			// user does not exist
			returnWithError("No Records Found");

			// TO DO: ask to sign up?
		}

		$stmt->close();
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
