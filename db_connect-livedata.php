<?php

// connection information for WordPress database on MediaTemple.net:
$host = "internal-db.sXXXXXX.gridserver.com";
$username = "dbXXXXXX";
$password = "<passwordgoeshere>";
$db_name = "dbXXXXXX_wordpress";

$mysqli_livedata = new mysqli($host, $username, $password, $db_name);
if(mysqli_connect_errno()) {
     echo "Error: Could not connect to database.";
     exit;
}

?>