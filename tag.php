<?php
include 'db.php';

$tag = mysqli_real_escape_string($conn, $_GET['name'] ?? '');

if (empty($tag)) {
    echo "No tag selected.";
    exit;
}

// Fetch posts with this tag
$query = "
    SELECT posts.* FROM posts 
    JOIN post_tags ON posts.id = post_tags.post_id 
    JOIN tags ON tags.id = post_tags.tag_id 
    WHERE tags.name = '$tag'
    ORDER BY posts.created_at DESC
";

$result = mysqli_query($conn, $query);
include 'components/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Posts tagged with <?= htmlspecialchars($tag) ?></title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>
    <main class="container main-container mt-5">
        <h2 class="mb-4">Posts tagged with <span class="text-primary"><?= htmlspecialchars($tag) ?></span></h2>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="alert alert-warning">No posts found for this tag.</div>
        <?php else: ?>
            <?php while ($post = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                        <a href="post/<?= $post['slug'] ?>" class="btn btn-primary">Read more →</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
            </main>
    <?php include 'components/footer.php'; ?>
</body>

</html>