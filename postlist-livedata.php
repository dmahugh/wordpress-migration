<html>
    <head>
        <title>Mahugh.com live posts</title>
    </head>
<body>

<?php

include 'db_connect-livedata.php';

// define and execute query ...
$query = "SELECT ID,post_date_gmt,post_title,post_content FROM wp_posts WHERE post_status='publish' and post_type='post'";
$result = $mysqli_livedata->query( $query );
$num_results = $result->num_rows;

if ( $num_results > 0) {
    // display results in a table ...
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
$mysqli_livedata->close();

?>

 </body>
 </html>