
<?php


   $inData = getRequestInfo();


   $searchResults = array();
   $searchCount = 0;
    $amount = $inData["ContactNumber"];


   $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
   if ($conn->connect_error)
   {
       returnWithError( $conn->connect_error );
   }
   else
   {
       $stmt = $conn->prepare("SELECT * FROM Contacts WHERE (UserID=?) AND Name LIKE (?) LIMIT ?");
       $contactName = "%" . $inData["Name"] . "%";
       $stmt->bind_param("sss", $inData["UserID"], $contactName, $amount);
       $stmt->execute();


       $result = $stmt->get_result();
       $searchCount = 0; //$result->num_rows;
       // $ogCount = $result->num_rows;
  
       while($row = $result->fetch_assoc())
       {
           $searchResults[$searchCount] = $row;
           $searchCount++;
          


           // if( $searchCount > 0 && $searchCount != $ogCount)
           // {
           //  $searchResults .= ",";
           // }
          
           // $searchCount--;
           // // $searchResults .= '"' . $row["FirstName"] . '"';
           // //"." means add
           // $searchResults .= '{"Name" : "' . $row["Name"]. '", "Phone" : "' . $row["Phone"]. '", "Email" : "' . $row["Email"]. '"}';
          
           // if($row = $result->fetch_assoc())
           // {
           //  returnWithInfo($searchResults);
           // }
       }


       if($searchCount > 0)
       {
           returnWithInfo(json_encode($searchResults));
       }
       else {
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
