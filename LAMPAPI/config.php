<?php
    // this is the config file to connect
    // the API to the SQL database

    // try and link to the database
    $link = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
    
    // Check connection
    if($link === false){
        die("Failed to connect to the database: " . mysqli_connect_error());
    }

?>