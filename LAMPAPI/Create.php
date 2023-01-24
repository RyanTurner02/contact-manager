<?php
    // this is where everything related to the 
    // creation of a contact will be written

    // config for sql connection
    require_once "config.php";
    
    // variables to hold contact data
    $Name = "";
    $Email = "";
    $Phone = "";
    $UserID = "";

    // variables to  hold error data
    $errName = "";
    $errEmail = "";
    $errPhone = "";
    $errUserID = "";
    
    // process data from front end
    // TO DO: get request method to input here
    // do we need to use JSON wrappers for that?
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // check Name for errors
        $Name = trim($_POST["Name"]);
        if(($Name == "") || (!filter_var($Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))) {
            $errName = "Error: Invalid Name";
        } else {
            // Name is ok, ready to proceed further
            echo "Name added successfully";
        }
        
        // check Email for errors
        $Email = trim($_POST["Email"]);
        if($Email == ""){
            $errEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to Phone
            echo "Email added successfully";
        }
        
        // check Phone for errors
        $Phone = trim($_POST["Phone"]);
        if(empty($Phone) || !filter_var($Phone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errPhone = "Error: Invalid Phone";     
        } else {
            // Phone is ok
            echo "Phone added successfully";
        }

        // check UserID Phone for errors
        $UserID = trim($_POST["userID"]);
        if(empty($UserID) || !filter_var($UserID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errPhone = "Error: Invalid UserID";     
        } else {
            // UserID Phone is ok
            echo "UserID added successfully";
        }
        
        // now input data into database as long as no errors
        if( ($errName == "") && ($errEmail == "") && ($errPhone == "") && ($errUserID == "")){

            // SQL statement to send data to database
            $sql = "INSERT INTO Contacts (Name, Phone, Email, UserID) VALUES (?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                

                mysqli_stmt_bind_param($stmt, "ssss", $Name, $Phone, $Email, $UserID);
                
                // add the contact to the database
                if(mysqli_stmt_execute($stmt)){
                    // contact successfully created

                    // TODO: Link front end here
                    // might need to do this w JSON wrapper functions
                    header("location: index.html");
                    exit();
                } else{
                    echo "Contact data had no errors but SQL failed to add it to the database";
                }
            }
            
            // sql call finished, go ahead and close it
            mysqli_stmt_close($stmt);

        } else {
            // data was not entered properly, so we should not send it to the database
            echo "Contact information was not entered correctly: ";
            echo $errName;
            echo $errEmail;
            echo $errPhone;
            echo $errUserID;
        }
        
        // finished comms with sql server, break communication
        mysqli_close($link);
}
?>