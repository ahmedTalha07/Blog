<?php
include '../db.php';
include 'auth.php';

$id = intval($_POST['id']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$featured = isset($_POST['featured']) ? 1 : 0;
$category_id = intval($_POST['category_id']);

// Handle image upload (optional)
$imageSQL = '';
if (!empty($_FILES['image']['name'])) {
    $image = time() . '_' . basename($_FILES['image']['name']);
    $target = '../uploads/' . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $imageSQL = ", image = '$image'";
}

// If making featured, unfeature all others first
if ($featured == 1) {
    mysqli_query($conn, "UPDATE posts SET featured = 0");
}

$update = "UPDATE posts SET 
    title = '$title', 
    content = '$content', 
    featured = $featured,
    category_id = $category_id
 
    $imageSQL
    WHERE id = $id";

$post_id = $_POST['id'];
$tags_input = $_POST['tags'];

// Step 1: Remove all previous tag links
mysqli_query($conn, "DELETE FROM post_tags WHERE post_id = $post_id");

// Step 2: Process and re-add updated tags
if (!empty($tags_input)) {
    $tags = array_map('trim', explode(',', $tags_input));
    foreach ($tags as $tag) {
        if ($tag === '') continue;

        $tag = strtolower($tag);
        $res = mysqli_query($conn, "SELECT id FROM tags WHERE name = '$tag'");
        if (mysqli_num_rows($res) > 0) {
            $tag_id = mysqli_fetch_assoc($res)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO tags (name) VALUES ('$tag')");
            $tag_id = mysqli_insert_id($conn);
        }

        // Insert relationship
        mysqli_query($conn, "INSERT INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
    }
}


mysqli_query($conn, $update);

header("Location: manage.php");
exit;
?>
