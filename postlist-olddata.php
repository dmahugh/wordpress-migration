<html>
    <head>
        <title>old posts from Doug's World</title>
    </head>
 <body>

<?php

include 'db_connect-olddata.php';

$query = "SELECT ID,post_date_gmt,post_title,post_content FROM wp_posts";
$result = $mysqli_olddata->query( $query );
$num_results = $result->num_rows;

if ( $num_results > 0) {
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>date/time</th>";
    echo "<th>Title</th>";
    echo "<th>Content</th>";
    echo "</tr>";
    while ( $row = $result->fetch_assoc() ) {
        extract($row);
        echo "<tr>";
        echo "<td>{$ID}</td>";
        echo "<td>{$post_date_gmt}</td>";
        echo "<td>{$post_title}</td>";
        echo "<td>{$post_content}</td>";
        echo "</tr>";
    }
     
    echo "</table>";
} else {
    echo "No records found.";
}

$result->free();
$mysqli_olddata->close();

?>

 </body>
 </html>