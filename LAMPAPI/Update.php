<?php
    // this file is where the update
    // feature will be implemented

    // get data from frontend
	$inData = getRequestInfo();
    
    // variables to hold contact data
    $newName = $inData["Name"];
    $newEmail = $inData["Email"];
    $newPhone = $inData["Phone"];
    $UserID = $inData["UserID"];    // we won't update this, since it is always linked to the account that created it
    $id = $inData["id"];            // this is how we will find the right contact to update

    // variables to track errors
    $errNewName = "";
    $errNewEmail = "";
    $errNewPhone = "";
    $errUserID = "";

    // once updated contact information is submitted
    // from the front end, find the contact and update it
    // connect to the server
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError($conn->connect_error);
    } else {

        // check Name
        if($newName == "" ||!filter_var($newName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]+$/")))) {
            $errNewName = "Error: Invalid Name";
        } else {
            echo "Name successfully updated";
        }
        
        // check Email
        if($newEmail == "") {
            $errNewEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to Phone
            echo "Email updated successfully";
        }

        // check Phone for errors
        if($newPhone == "") {
            $errNewPhone = "Error: Invalid Phone";     
        } else {
            // Phone is ok
            echo "Phone Updated successfully";
        }

        // now input data into database as long as no errors
        if( ($errNewName == "") && ($errNewEmail == "") && ($errNewPhone == "")) {
            
            // SQL statement to send data to database
            $stmt = $conn->prepare("UPDATE Contacts SET Name = ?, Phone = ?, Email = ? WHERE ID = ?");
            $stmt->bind_param("sssi", $newName, $newPhone, $newEmail, $id);
                
            // add the contact to the database
            if($stmt->execute()){
                // contact successfully created
                // link to front end
                returnWithError("");
            } else {
                echo "Contact data had no errors but SQL failed to add it to the database";
            }
            
            // finished with statement, so close it 
            $stmt->close();

        } else {
            // data was not entered properly, so we should not send it to the database
            echo "Contact information was not entered correctly: ";
            echo $errNewName;
            echo $errNewEmail;
            echo $errNewPhone;
            echo $errNewUserID;
        }
        
        // disconnect from server 
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