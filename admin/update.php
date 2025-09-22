<?php
include '../db.php';
include 'auth.php';

$id         = intval($_POST['id']);
$title      = mysqli_real_escape_string($conn, $_POST['title']);
$content    = mysqli_real_escape_string($conn, $_POST['content']);
$featured   = isset($_POST['featured']) ? 1 : 0;
$category_id = intval($_POST['category_id']);

// default to old image
$image = mysqli_real_escape_string($conn, $_POST['current_image'] ?? '');

// only rename once
if (!empty($_FILES['image']['name'])) {
    $original = basename($_FILES['image']['name']);
    $newName  = time() . '_' . $original;

    if (move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$newName")) {
        $image = $newName;
    }
}

if ($featured == 1) {
    mysqli_query($conn, "UPDATE posts SET featured = 0");
}

$update = "
UPDATE posts SET
    title       = '$title',
    content     = '$content',
    featured    = $featured,
    category_id = $category_id,
    image       = '$image'
WHERE id = $id
";

// run the update ONCE, with error check
if (!mysqli_query($conn, $update)) {
    die('MySQL error: '.mysqli_error($conn).'<br>Query: '.$update);
}

// tags update
$post_id    = $id;
$tags_input = $_POST['tags'] ?? '';

mysqli_query($conn, "DELETE FROM post_tags WHERE post_id = $post_id");

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
