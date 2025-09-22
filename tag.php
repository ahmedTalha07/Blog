<?php
include 'db.php';

$tag = mysqli_real_escape_string($conn, $_GET['name'] ?? '');

if (empty($tag)) {
    echo "No tag selected.";
    exit;
}

// Fetch posts with this tag
$query = "
    SELECT posts.* 
    FROM posts 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
</head>

<body>
    <main class="container main-container mt-5">
        <h2 class="mb-4">
            Posts tagged with <span class="text-primary"><?= htmlspecialchars($tag) ?></span>
        </h2>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="alert alert-warning">No posts found for this tag.</div>
        <?php else: ?>
            <div class="row g-4" data-masonry='{"percentPosition": true }'>
                <?php while ($post = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card mb-4 shadow-sm">
                            <?php if (!empty($post['image'])): ?>
                                <img class="card-img-top"
                                    src="/uploads/<?= htmlspecialchars($post['image']) ?>"
                                    alt="<?= htmlspecialchars($post['title']) ?>"
                                    style="height:200px;object-fit:cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="small text-muted">
                                    <?= date('F j, Y', strtotime($post['created_at'])) ?>
                                    <!-- badge for tag -->
                                    | <span class="badge bg-primary"><?= htmlspecialchars($tag) ?></span>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                <p class="card-text">
                                    <?= substr(strip_tags($post['content']), 0, 100) ?>...
                                </p>
                                <a href="post/index.php?slug=<?= htmlspecialchars($post['slug']) ?>"
                                    class="btn btn-outline-primary btn-sm">
                                    Read Full Article →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>