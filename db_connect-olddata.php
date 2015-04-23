<?php

// connection information for temp copy of old WordPress data on server:
$host = "internal-db.sXXXXXX.gridserver.com";
$username = "dbXXXXXX";
$password = "<passwordgoeshere>";
$db_name = "dbXXXXXX_oldwpdata";

$mysqli_olddata = new mysqli($host, $username, $password, $db_name);
if(mysqli_connect_errno()) {
     echo "Error: Could not connect to database.";
     exit;
}

?>