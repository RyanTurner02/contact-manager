<?php
    // this is where everything related to the 
    // creation of a contact will be written

    // config for sql connection
    require_once "config.php";
    
    // variables to hold contact data
    $name = "";
    $email = "";
    $number = "";

    // variables to  hold error data
    $errName = "";
    $errEmail = "";
    $errNumber = "";
    
    // process data from front end
    // TO DO: get request method to input here
    // do we need to use JSON wrappers for that?
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // check name for errors
        $name = trim($_POST["name"]);
        if(($name == "") || (!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/"))))) {
            $errName = "Error: Invalid Name";
        } else {
            // name is ok, ready to proceed further
            echo "Name added successfully";
        }
        
        // check email for errors
        $email = trim($_POST["email"]);
        if($email == ""){
            $errEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to number
            echo "email added successfully";
        }
        
        // check number for errors
        $number = trim($_POST["number"]);
        if(empty($number) || !filter_var($number, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errNumber = "Error: Invalid Number";     
        } else {
            // number is ok
            echo "Number added successfully";
        }
        
        // now input data into database as long as no errors
        if( ($errName == "") && ($errEmail == "") && ($errNumber == "")){

            // SQL statement to send data to database
            $sql = "INSERT INTO contacts (name, address, number) VALUES (?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                

                mysqli_stmt_bind_param($stmt, "sss", $tempName, $tempEmail, $tempNumber);
                
                // set sql params equal to data
                $tempName = $name;
                $tempEmail = $email;
                $tempNumber = $number;
                
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
            echo $errNumber;
        }
        
        // finished comms with sql server, break communication
        mysqli_close($link);
}
?>