<?php

	$inData = getRequestInfo();
	
	// New Input variable for front end. This variable will be 0 when the user logins in intitally. 
	// When called from the more contacts button this value will be 14 and will increment by 5 each time its called.
	$ContactNumber = $inData["ContactNumber"];
	$searchResults = array();
	$searchCount = 0;
    
	//$searchIncrement = 14;
    
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	
	else
	{
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (UserID=?) LIMIT ?");
		//$colorName = "%" . $inData["search"] . "%";
		$stmt->bind_param("si", $inData["UserID"], $ContactNumber);
		$stmt->execute();

		$result = $stmt->get_result();
		//$searchCount = ContactNumber; //$result->num_rows;
		// $ogCount = $result->num_rows;
		
		// the while loop will run 5 times when the cont
		//if($ContactNumber > 0)
		//{
			//$searchIncrement = $ContactNumber + 5;
		//}
		
		while($row = $result->fetch_assoc() )
		{
			$searchResults[$searchCount] = $row;
			$searchCount++;
			//$ContactNumber++;

			// This if statement breaks the loop when the added contacts number has been reached 
			//if($searchIncrement <= $searchCount)
			//{
				//break;
			//}
			//if($searchCount > 0)
			// if( $searchCount > 0 && $searchCount != $ogCount)
			// {
			// 	$searchResults .= ",";
			// }
			
      		// $searchCount--;
			// // $searchResults .= '"' . $row["FirstName"] . '"';
			// //"." means add
			// $searchResults .= '{"Name" : "' . $row["Name"]. '", "Phone" : "' . $row["Phone"]. '", "Email" : "' . $row["Email"]. '"}';
      		
			// if($row = $result->fetch_assoc())
      		// {
        	// 	returnWithInfo($searchResults);
      		// }
		}

		if($searchCount > 0)
		{
			returnWithInfo(json_encode($searchResults));
		}
		
		else 
		{
			returnWithError("No records found");
		}
		
		//if($searchCount == 0)
		//{
			//returnWithError( "End of Records" );
		//}
		
		//else
		//{
			//returnWithInfo( $searchResults );
		//}

		$stmt->close();
		$conn->close();
	}

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

	function returnWithInfo( $searchResults )
	{
		// $retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $searchResults );
	}

?>

