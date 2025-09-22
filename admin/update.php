<?php
include '../db.php';
include 'auth.php';

$id         = intval($_POST['id']);
$title      = mysqli_real_escape_string($conn, $_POST['title']);
$content    = mysqli_real_escape_string($conn, $_POST['content']);
$featured   = isset($_POST['featured']) ? 1 : 0;
$category_id = intval($_POST['category_id']);

// default to old image from hidden field
$image = mysqli_real_escape_string($conn, $_POST['current_image'] ?? '');

// if a new file was uploaded, overwrite $image and move the file
if (!empty($_FILES['image']['name'])) {
    $newName = time() . '_' . basename($_FILES['image']['name']);
    $target  = '../uploads/' . $newName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image = $newName; // update to new filename
    }
}

// if marking as featured, unfeature all others first
if ($featured == 1) {
    mysqli_query($conn, "UPDATE posts SET featured = 0");
}

// update post (always sets image column now)
$update = "
    UPDATE posts SET
        title       = '$title',
        content     = '$content',
        featured    = $featured,
        category_id = $category_id,
        image       = '$image'
    WHERE id = $id
";
mysqli_query($conn, $update);

// update tags
$post_id    = $id;
$tags_input = $_POST['tags'] ?? '';

// remove all old tag links
mysqli_query($conn, "DELETE FROM post_tags WHERE post_id = $post_id");

// re-insert updated tags
if (!empty($tags_input)) {
    $tags = array_map('trim', explode(',', $tags_input));
    foreach ($tags as $tag) {
        if ($tag === '') continue;
        $tag = strtolower(mysqli_real_escape_string($conn, $tag));

        $res = mysqli_query($conn, "SELECT id FROM tags WHERE name = '$tag'");
        if (mysqli_num_rows($res) > 0) {
            $tag_id = mysqli_fetch_assoc($res)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO tags (name) VALUES ('$tag')");
            $tag_id = mysqli_insert_id($conn);
        }

        mysqli_query($conn, "INSERT INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
    }
}

header("Location: manage.php");
exit;