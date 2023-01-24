<?php
    // this file is where the update
    // feature will be implemented

    // get data from frontend
	$inData = getRequestInfo();
    
    // variables to hold contact data
    $newName = $inData["Name"];
    $newEmail = $inData["Email"];
    $newPhone = $inData["Phone"];
    $UserID = $inData["UserID"];
    $id = $inData["id"];

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
        if($newName == "" ||!filter_var($newName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))) {
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
        if($newPhone == "" || !filter_var($newPhone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errNewPhone = "Error: Invalid Phone";     
        } else {
            // Phone is ok
            echo "Phone added successfully";
        }

        // check userID
        if($UserID == "" || !filter_var($UserID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errUserID = "Error: Invalid UserID";     
        } else {
            // Phone is ok
            echo "UserID added successfully";
        }

        // now input data into database as long as no errors
        if( ($errNewName == "") && ($errNewEmail == "") && ($errNewPhone == "") && ($errUserID == "")) {
            
            // SQL statement to send data to database
            $stmt = $conn->prepare("UPDATE Contacts(Name, Phone, Email, UserID) VALUES(?, ?, ?, ?)");
            
            $stmt->bind_param("sssi", $newName, $newPhone, $newEmail, $UserID);
                
            // add the contact to the database
            if($stmt->execute()){
                // contact successfully created

                // TODO: Link front end here
                // might need to do this w JSON wrapper functions
                header("location: index.html");
                exit();
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