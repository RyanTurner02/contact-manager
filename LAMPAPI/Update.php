<?php
    // this file is where the update
    // feature will be implemented

    // include config
    require_once "config.php";

    // variables to hold contact info
    $Name = "";
    $Email = "";
    $Phone = "";
    $UserID = "";
    $id = "";

    // variables to track errors
    $errName = "";
    $errEmail = "";
    $errPhone = "";
    $errUserID = "";

    // once updated contact information is submitted
    // from the front end, find the contact and update it
    if(isset($_POST["id"]) && ($_POST["id"] != "")){

        // store id into local
        $id = $_POST["id"];
        
        // check Name
        $Name = trim($_POST["Name"]);
        if($Name == "" ||!filter_var($Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))) {
            $Name_err = "Error: Invalid Name";
        } else {
            echo "Name successfully updated";
        }
        
        // check Email
        $Email = trim($_POST["Email"]);
        if($Email == "") {
            $errEmail = "Error: Invalid Email";     
        } else {
            // Email ok, move to Phone
            echo "Email updated successfully";
        }
        
        // check Phone for errors
        $Phone = trim($_POST["Phone"]);
        if(empty($Phone) || !filter_var($Phone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errPhone = "Error: Invalid Phone";     
        } else {
            // Phone is ok
            echo "Phone added successfully";
        }

        // check userID
        if(empty($UserID) || !filter_var($UserID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[1-9]^+$/")))) {
            $errUserID = "Error: Invalid UserID";     
        } else {
            // Phone is ok
            echo "UserID added successfully";
        }

        // now input data into database as long as no errors
        if( ($errName == "") && ($errEmail == "") && ($errPhone == "") && ($errUserID == "")) {
            
            // create sql statement to send to database
            $sql = "UPDATE Contacts SET Name=?, Phone=?, Email=?, UserID=? WHERE id=?";
            
            if($stmt = mysqli_prepare($link, $sql)) {

                // bind variables to sql parameters
                mysqli_stmt_bind_param($stmt, "ssssi", $Name, $Phone, $Email, $UserID, $id);
                
                // update the sql database and send to front end
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
            $sql = "SELECT * FROM Contacts WHERE id = ?";
            if($stmt = mysqli_prepare($link, $sql)) {
                // bind tempID to statement to send
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                // try and execute the sql command to the database
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        // only one entry in database
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        //  copy data from entry into local variables
                        $Name = $row["Name"];
                        $Phone = $row["Phone"];
                        $Email = $row["Email"];
                        $UserID - $row["UserID"];
                        
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