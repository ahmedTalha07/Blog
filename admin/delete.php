<?php
include 'auth.php';
?>

<?php
include '../db.php';

if (!isset($_GET['id'])) {
    die("No post ID provided.");
}

$id = intval($_GET['id']);

// Optional: fetch the image filename to delete from disk
$res = mysqli_query($conn, "SELECT image FROM posts WHERE id = $id");
if ($row = mysqli_fetch_assoc($res)) {
    if (!empty($row['image'])) {
        @unlink("../uploads/" . $row['image']);
    }
}

mysqli_query($conn, "DELETE FROM posts WHERE id = $id");

header("Location: manage.php");
exit;
?>
