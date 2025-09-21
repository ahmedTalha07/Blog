<?php
include 'db.php';

$post_id = $_POST['post_id'] ?? '';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$content = trim($_POST['content'] ?? '');

if ($post_id && $name && $email && $content) {
    $query = "
        INSERT INTO comments (post_id, name, email, content)
        VALUES ('$post_id', '$name', '$email', '$content')
    ";
    $success = mysqli_query($conn, $query);

    // Get post slug for redirect
    $slugRes = mysqli_query($conn, "SELECT slug FROM posts WHERE id = $post_id LIMIT 1");
    $slugRow = mysqli_fetch_assoc($slugRes);
    $slug = $slugRow['slug'] ?? 'index';

    if ($success) {
        header("Location: /post/index.php?slug=$slug&success=1");
    } else {
        header("Location: post/index.php?slug=$slug&error=1");
    }
} else {
    header("Location: post/index.php?slug=$slug&error=1");
    exit;
}