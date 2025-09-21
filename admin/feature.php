
<?php
include '../db.php';
include 'auth.php';

if (!isset($_GET['id'])) {
    die("Post ID is required.");
}

$id = intval($_GET['id']);

// First, remove featured status from all posts
mysqli_query($conn, "UPDATE posts SET featured = 0");

// Then, set the selected one as featured
mysqli_query($conn, "UPDATE posts SET featured = 1 WHERE id = $id");

// Redirect back to manage panel
header("Location: manage.php");
exit;
?>
