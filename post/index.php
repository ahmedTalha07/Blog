<?php
// post/index.php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    echo "No slug provided.";
    exit;
}

/* Fetch post safely */
$stmt = mysqli_prepare($conn, "
    SELECT posts.*, categories.name AS category_name
    FROM posts
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE slug = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$postResult = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($postResult);

if (!$post) {
    echo "Post not found.";
    exit;
}

$post_id = intval($post['id']);

/* Fetch tags */
$tagList = [];
$tagStmt = mysqli_prepare($conn, "
    SELECT t.name
    FROM tags t
    JOIN post_tags pt ON t.id = pt.tag_id
    WHERE pt.post_id = ?
");
mysqli_stmt_bind_param($tagStmt, "i", $post_id);
mysqli_stmt_execute($tagStmt);
$tagsRes = mysqli_stmt_get_result($tagStmt);
while ($r = mysqli_fetch_assoc($tagsRes)) $tagList[] = $r['name'];

/* Fetch comments */
$comments = false;
$cStmt = mysqli_prepare($conn, "
    SELECT name, content, created_at
    FROM comments
    WHERE post_id = ?
    ORDER BY created_at DESC
");
mysqli_stmt_bind_param($cStmt, "i", $post_id);
mysqli_stmt_execute($cStmt);
$comments = mysqli_stmt_get_result($cStmt);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($post['title']) ?> — Talha Ahmed</title>
  <link rel="icon" href="../assets/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
  <style>
    /* small helpers */
    .post-featured {
      width: 100%;
      max-height: 420px;
      object-fit: cover;
      display: block;
      border-radius: .375rem;
    }
    /* make the sidebar content start at top and allow widgets to use full width of sidebar */
    .post-row { align-items: flex-start; }
    .post-content { max-width: 720px; }
    /* optional: make sidebar sticky on large screens */
    @media (min-width: 992px) {
      .sidebar-sticky { position: sticky; top: 1.75rem; }
    }
  </style>
</head>
<body>
  <?php include '../components/navbar.php'; ?>

  <header class="py-5 bg-primary text-light border-bottom mb-4">
    <div class="container text-center">
      <h1 class="fw-bolder"><?= htmlspecialchars($post['title']) ?></h1>
      <p class="mb-0"><?= date('F j, Y', strtotime($post['created_at'])) ?> — <?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?></p>
    </div>
  </header>

  <main class="container my-5">
    <div class="row post-row gx-4">
      <!-- LEFT: image + content -->
      <div class="col-lg-8">
        <!-- featured image at top of left column -->
        <img src="../uploads/<?= htmlspecialchars($post['image']) ?>"
             alt="<?= htmlspecialchars($post['title']) ?>"
             class="post-featured mb-4">

        <!-- post body -->
        <div class="fs-5 lh-lg post-content mb-4">
          <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

        <!-- tags -->
        <?php if (!empty($tagList)): ?>
          <div class="mb-4">
            <strong>Tags:</strong>
            <?php foreach ($tagList as $tag): ?>
              <a href="/tag.php?name=<?= urlencode($tag) ?>" class="badge bg-secondary me-1"><?= htmlspecialchars($tag) ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- comments -->
        <section class="mb-5">
          <h4 class="mb-4">Comments</h4>
          <?php if ($comments && mysqli_num_rows($comments) > 0): ?>
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

        <!-- comment form -->
        <section class="mb-5">
          <h4 class="mb-3">Leave a Comment</h4>
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Your comment was submitted!</div>
          <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">Failed to submit comment. Please fill all fields.</div>
          <?php endif; ?>
          <form action="/save_comment.php" method="POST" class="card shadow-sm p-4">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
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

      <!-- RIGHT: widgets (this column contains the widgets; widgets.php must NOT include col-* classes) -->
      <aside class="col-lg-4">
        <div class="sidebar-sticky">
          <?php include '../components/widgets.php'; ?>
        </div>
      </aside>
    </div> <!-- /.row -->
  </main> <!-- /.container -->

  <?php include '../components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
