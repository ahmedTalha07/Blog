<?php
include 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if (!$q) {
    die("Please enter a search term.");
}

$search = mysqli_real_escape_string($conn, $q);
$result = mysqli_query(
    $conn,
    "SELECT * FROM posts 
     WHERE title LIKE '%$search%' OR content LIKE '%$search%' 
     ORDER BY created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Search results for <?= htmlspecialchars($q) ?>">
  <meta name="author" content="Talha Ahmed">
  <title>Search results for "<?= htmlspecialchars($q) ?>" - Talha Ahmed Blog</title>
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <?php include './components/navbar.php'; ?>
  <!-- Header -->
  <?php include './components/header.php'; ?>

  <!-- Search results -->
  <main class="flex-grow-1 container">
    <div class="row">
      <div class="col-lg-8">
        <h2 class="mb-4">
          Search Results for: <em><?= htmlspecialchars($q) ?></em>
        </h2>
        <hr>

        <?php if (mysqli_num_rows($result) > 0): ?>
          <div class="row">
            <?php while ($post = mysqli_fetch_assoc($result)): ?>
              <div class="col-md-6 mb-3">
                <div class="card mb-4 shadow-sm h-100">
                  <?php if (!empty($post['image'])): ?>
                    <img class="card-img-top" 
                         src="/uploads/<?= htmlspecialchars($post['image']) ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         style="height:200px;object-fit:cover;">
                  <?php endif; ?>
                  <div class="card-body">
                    <div class="small text-muted">
                      <?= date('F j, Y', strtotime($post['created_at'])) ?>
                    </div>
                    <h5 class="card-title">
                      <?= htmlspecialchars($post['title']) ?>
                    </h5>
                    <p class="card-text">
                      <?= substr(strip_tags($post['content']), 0, 100) ?>…
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
        <?php else: ?>
          <p class="text-muted">
            No results found for "<strong><?= htmlspecialchars($q) ?></strong>"
          </p>
        <?php endif; ?>
      </div>

      <!-- Sidebar widgets -->
      <?php include './components/widgets.php'; ?>
    </div>
  </main>

  <!-- Footer -->
  <?php include './components/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
