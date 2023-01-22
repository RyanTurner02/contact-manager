<?php
    // this file is where the delete
    // feature will be implemented

    // Include config file
    require_once "config.php";

    // pull ID of contact to delete from front end
    if(isset($_POST["id"]) && ($_POST["id"]) != "") {
        
        // delete statmenet to send to SQL server
        $sql = "DELETE FROM employees WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)) {

            // bind ID parameter to delete statement
            mysqli_stmt_bind_param($stmt, "i", $tempID);
            
            // set ID parameter to ID from front end
            $tempID = trim($_POST["id"]);
            
            // send SQL delete command 
            if(mysqli_stmt_execute($stmt)){
                // delete successful
                echo "successfully deleted contact";
                // TO DOO:
                // redirect to front end page here

                // exit file
                exit();
            } else {
                echo "failed to execute SQL delete command";
            }
        }
        
        // finished with SQL statement
        mysqli_stmt_close($stmt);
        
        // finished comms with SQL server, disconnect 
        mysqli_close($link);

    } else {
        // either ID does not exist, or
        // invalid ID sent by user 
        if(trim($_GET["id"] = "")) {
            
            // user failed to enter ID
            // TO DO:
            // redirect here or tell user failed
            /*header("location: error.php");*/
            
            echo "Failed to read input ID";
            exit();
        }
    }
?>