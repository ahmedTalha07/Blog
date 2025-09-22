<?php
include '../db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    echo "No slug provided.";
    exit;
}

// Use prepared statement for safety
$stmt = mysqli_prepare($conn, "SELECT * FROM posts WHERE slug = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    echo "Post not found.";
    exit;
}

// Fetch category name
$cat_name = 'Uncategorized';
if ($post['category_id']) {
    $cid = intval($post['category_id']);
    $c = mysqli_query($conn, "SELECT name FROM categories WHERE id = $cid");
    if ($cRow = mysqli_fetch_assoc($c)) {
        $cat_name = $cRow['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars(substr(strip_tags($post['content']), 0, 150)) ?>">
    <meta name="author" content="Talha Ahmed">
    <title><?= htmlspecialchars($post['title']) ?> - Talha Ahmed Blog</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar (same as index.php) -->
    <?php include '../components/navbar.php'; ?>
    <!-- Hero header with post title -->
    <header class="py-5 bg-primary text-light border-bottom mb-4">
        <div class="container text-center">
            <h1 class="fw-bolder"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="mb-0"><?= date('F j, Y', strtotime($post['created_at'])) ?> — <?= htmlspecialchars($cat_name) ?></p>
        </div>
    </header>

    <!-- Article content -->
    <main class="container my-5">
        <div class="row">
            <!-- Main content -->
            <div class="col-lg-8">
                <article class="mb-5">
                    <img src="../uploads/<?= htmlspecialchars($post['image']) ?>"
                        alt="<?= htmlspecialchars($post['title']) ?>"
                        class="img-fluid rounded mb-4">
                    <div class="fs-5 lh-lg">
                        <?= nl2br(htmlspecialchars($post['content'])) ?>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($tagList)): ?>
                        <div class="mt-4">
                            <strong>Tags:</strong>
                            <?php foreach ($tagList as $tag): ?>
                                <a href="/tag.php?name=<?= urlencode($tag) ?>"
                                    class="badge bg-secondary me-1"><?= htmlspecialchars($tag) ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

                <!-- Comments -->
                <section class="mb-5">
                    <h4 class="mb-4">Comments</h4>
                    <?php if (mysqli_num_rows($comments) > 0): ?>
                        <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-1"><?= htmlspecialchars($comment['name']) ?></h6>
                                    <small class="text-muted"><?= date("F j, Y g:i A", strtotime($comment['created_at'])) ?></small>
                                    <p class="mt-2"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">No comments yet.</p>
                    <?php endif; ?>
                </section>

                <!-- Comment form -->
                <section class="mb-5">
                    <h4 class="mb-3">Leave a Comment</h4>
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">Your comment was submitted!</div>
                    <?php elseif (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">Failed to submit comment. Please fill all fields.</div>
                    <?php endif; ?>
                    <form action="/save_comment.php" method="POST" class="card shadow-sm p-4">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <textarea name="content" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>
                </section>
            </div>

            <!-- Sidebar widgets -->
            <div class="col-lg-4">
                <?php include '../components/widgets.php'; ?>
            </div>
        </div>
    </main>


    <!-- Footer -->
    <?php include '../components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>