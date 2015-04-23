<html>
<body>

<?php
// mergedata.php - merge old WordPress blog data with live data
// old data is from Doug's World 2005-2007, stored in MediaTemple database db140862_oldwpdata
// live data is from Mahugh.com 2012-2013, stored in MediaTemple database db140862_wordpress

echo "<br/>Initializing ...";

require '../../wp-load.php';       // include WordPress API functionality such as wp_insert_post() and wp_insert_comment()
require 'db_connect-livedata.php'; // creates $mysqli_livedata for access to db140862_wordpress
require 'db_connect-olddata.php';  // creates $mysqli_olddata  for access to db140862_oldwpdata

echo "</br>Begin main loop (iterating through old data) ...";

$query = "SELECT ID,post_date,post_date_gmt,post_title,post_content FROM wp_posts";
$result = $mysqli_olddata->query( $query );
$num_results = $result->num_rows;

$posts_processed = 0;

if ( $num_results > 0) {
    while ( $row = $result->fetch_assoc() ) {
        extract($row);
        echo "<br/>Migrating post: old ID={$ID} date={$post_date} title={$post_title}";

        // save the post ...
        $post_data = array(
            'post_title'    => $post_title,
            'post_content'  => $post_content,
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_date'     => $post_date,
            'post_date_gmt' => $post_date_gmt
            );
        // $postID = the auto-incremented ID assigned to this post 
        $postID = wp_insert_post($post_data);
        if ( is_wp_error($postID) )
            exit( $postID->get_error_message() );

        // save the comments ...
        $commentquery = "SELECT comment_author,comment_author_email,
            comment_author_url,comment_content,user_id,comment_author_IP,
            comment_agent,comment_date,comment_date_gmt,user_id FROM wp_comments
            WHERE comment_post_ID=$ID AND comment_approved='1'"; // note use of $ID, which is the old/original ID of this post (in the old database)
        $commentresult = $mysqli_olddata->query($commentquery);
        $num_comments = $commentresult->num_rows;
        if ( $num_comments > 0) {
            while ( $commentrow = $commentresult->fetch_assoc() ) {
                extract($commentrow);
                $comment_data = array(
                    'comment_post_ID' => $postID, // use the newly assigned ID, to associate comments with the new post
                    'comment_author' => $comment_author,
                    'comment_author_email' => $comment_author_email,
                    'comment_author_url' => $comment_author_url,
                    'comment_content' => $comment_content,
                    'comment_type' => '',
                    'comment_parent' => 0,
                    'user_id' => $user_id,
                    'comment_author_IP' => $comment_author_IP,
                    'comment_agent' => $comment_agent,
                    'comment_date' => $comment_date,
                    'comment_date_gmt' => $comment_date_gmt,
                    'comment_approved' => 1,
                );
                $commentID = wp_insert_comment($comment_data);
                if ( is_wp_error($commentID) )
                    exit( $commentID->get_error_message() );
            }
        }

        $posts_processed += 1;
        // if ($posts_processed >= 1) break; // for testing, only process the first post
    }
} else {
    echo "No records found.";
}

echo "<br/>Total posts migrated: {$posts_processed}";

$result->free();

$mysqli_livedata->close();
$mysqli_olddata->close();

?>

</body>
</html>