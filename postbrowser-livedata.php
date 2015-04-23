<html>
<body>

<form action="postbrowser-livedata.php" method="post">
Post ID: <input type="text" name="postid">
<input type="submit">
</form>

<?php

include 'db_connect-livedata.php';

if (isset($_POST["postid"])) {
	// user entered a desired post ID
    $currentID = $_POST["postid"];
} else {
	// user has not entered a desired post ID, so default to the latest post
	$query = "SELECT ID FROM wp_posts order by ID desc LIMIT 1";
	$result = $mysqli_livedata->query( $query );
	$num_results = $result->num_rows;
	if ( $num_results > 0) {
		$row = $result->fetch_assoc();
		extract($row);
	    $currentID = $ID;
	} else {
	     echo "No records found.";
    }
    $result->free();
}

// display the specified blog post ...
echo "<table border='1'>";

$query = "SELECT ID,post_date_gmt,post_title,post_content FROM wp_posts WHERE ID=$currentID";
$result = $mysqli_livedata->query( $query );
$num_results = $result->num_rows;
if ( $num_results > 0) {
	$row = $result->fetch_assoc();
	extract($row);
    echo "<tr><th>ID = {$currentID}</th><th>Date = {$post_date_gmt}</th><th>Title = {$post_title}</th></tr>";
    echo "<tr><td colspan=3>{$post_content}</td></tr>";
    $result->free();
    // get the comments for this post ...
    $query = "SELECT comment_ID,comment_post_ID,comment_author,comment_date,comment_content FROM wp_comments WHERE comment_post_ID=$currentID AND comment_approved='1'";
    $result = $mysqli_livedata->query( $query );
    $num_results = $result->num_rows;
    if ( $num_results > 0) {
        while ( $row = $result->fetch_assoc() ) {
            extract($row);
            echo "<tr><td>Comment by: {$comment_author}</td><td>{$comment_date}</td><td>{$comment_content}</td></tr>";
        }
    } else {
        echo "<tr><td colspan=3>No comments for post ID {$currentID}</td></tr>";
    }
    $result->free();
} else {
    echo "NOT FOUND! Blog post ID = ".$currentID;
}

echo "</table>";

$mysqli_livedata->close();

?>

</body>
</html> 