<?php
include '../db.php';
include 'auth.php';
// Handle image upload
$image = '';
if (!empty($_FILES['image']['name'])) {
    $image = time() . '_' . basename($_FILES['image']['name']);
    $target = '../uploads/' . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
}

$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$featured = isset($_POST['featured']) ? 1 : 0;
$category_id = intval($_POST['category_id']);

// If this post is featured, un-feature all others first
if ($featured == 1) {
    mysqli_query($conn, "UPDATE posts SET featured = 0");
}

function generateSlug($title)
{
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug); // replace non-letters/numbers
    $slug = preg_replace('/-+/', '-', $slug);          // collapse dashes
    return rtrim($slug, '-');
}

$slug = generateSlug($title);

// Check uniqueness
$check = mysqli_query($conn, "SELECT id FROM posts WHERE slug = '$slug'");
if (mysqli_num_rows($check)) {
    $slug .= '-' . time(); // make it unique
}


// ...
$query = "INSERT INTO posts (title, slug, content, image, featured, category_id)
          VALUES ('$title', '$slug', '$content', '$image', $featured, $category_id)";



mysqli_query($conn, $query);


header("Location: ../index.php");

$post_id = mysqli_insert_id($conn);
$tags_input = $_POST['tags'];

if (!empty($tags_input)) {
    $tags = array_map('trim', explode(',', $tags_input));
    foreach ($tags as $tag) {
        if ($tag === '') continue;

        // Check if tag already exists
        $tag = strtolower($tag);
        $res = mysqli_query($conn, "SELECT id FROM tags WHERE name = '$tag'");
        if (mysqli_num_rows($res) > 0) {
            $tag_id = mysqli_fetch_assoc($res)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO tags (name) VALUES ('$tag')");
            $tag_id = mysqli_insert_id($conn);
        }

        // Link tag with post
        mysqli_query($conn, "INSERT INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
    }
}
exit;
?>
