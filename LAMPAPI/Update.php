<?php
    // this file is where the update
    // feature will be implemented

    // include config
    require_once "config.php";

    // variables to hold contact info
    $name = "";
    $email = "";
    $number = "";
    $id = "";

    // variables to track errors
    $errName = "";
    $errEmail = "";
    $errNumber = "";

    // once updated contact information is submitted
    // from the front end, find the contact and update it
    if(isset($_POST["id"]) && ($_POST["id"] != "")){

        // store id into local
        $id = $_POST["id"];
        
        // check name
        $name = trim($_POST["name"]);
        if($name == "" ||!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))) {
            $name_err = "Error: Invalid Name";
        } else {
            echo "Name successfully updated";
        }
        
        // check email
        $email = trim($_POST["email"]);
        if($email == "") {
            $errEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to number
            echo "email updated successfully";
        }
        
        // Validate number
        // check number for errors
        $number = trim($_POST["number"]);
        if(empty($number) || !filter_var($number, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errNumber = "Error: Invalid Number";     
        } else {
            // number is ok
            echo "Number added successfully";
        }
        
        // now input data into database as long as no errors
        if( ($errName == "") && ($errEmail == "") && ($errNumber == "")) {
            
            // create sql statement to send to database
            $sql = "UPDATE contacts SET name=?, email=?, number=? WHERE id=?";
            
            if($stmt = mysqli_prepare($link, $sql)) {

                // bind variables to sql parameters
                mysqli_stmt_bind_param($stmt, "sssi", $tempName, $tempEmail, $tempNumber, $tempID);
                
                // Set parameters equal to the 
                // local updated values for our contact
                $tempName = $name;
                $tempEmail = $email;
                $tempNumber = $number;
                $tempID = $id;
                
                // update the sql database 
                if(mysqli_stmt_execute($stmt)){
                    
                    // update finished successfully
                    // TO DO:
                    // send user to search page?
                    /*header("location: index.php");*/

                    // finished updating, exit
                    exit();
                } else {
                    echo "Failed to execute SQL statement when attempting to update contact";
                }
            }
            
            // finished with statement, so close it 
            mysqli_stmt_close($stmt);
        }
        
        // disconnect from server 
        mysqli_close($link);

    } else {

        // either the ID entered was empty, 
        // or we couldn't get it via the post method
        // so lets try with the get method
        if(isset($_GET["id"]) && (trim($_GET["id"]) != "")) {

            // copy the id, we were able to get it
            $id =  trim($_GET["id"]);
            
            // since we couldn't update the contact above,
            // lets try and select it
            $sql = "SELECT * FROM contacts WHERE id = ?";
            if($stmt = mysqli_prepare($link, $sql)) {
                // bind tempID to statement to send
                mysqli_stmt_bind_param($stmt, "i", $tempID);
                
                // copy id to the parameter to be sent
                $tempID = $id;
                
                // try and execute the sql command to the database
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        // only one entry in database
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        //  copy data from entry into local variables
                        $name = $row["name"];
                        $email = $row["email"];
                        $number = $row["number"];
                    } else {
                        
                        // TO DO: 
                        // redirect here, no id found
                        /*header("location: error.php");*/
                        exit();
                    }
                    
                } else {
                    echo "Failed to execute SQL statement when attempting to find contact ID";
                }
            }
            
            // statement has sent or attempted to send, we are finished 
            mysqli_stmt_close($stmt);
            
            // disconnect from database 
            mysqli_close($link);
        }  else {
            
            // TO DO:
            // redirect here, no id found
            /*header("location: error.php");*/

            // exit program
            exit();
        }
    }

?>