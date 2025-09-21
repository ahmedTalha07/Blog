<?php 
include 'db.php';

$id = intval($_GET['id'] ?? 0);

// Fetch category name
$cat_name = 'Category';
if ($id) {
    $catRes = mysqli_query($conn, "SELECT name FROM categories WHERE id = $id");
    if ($catRow = mysqli_fetch_assoc($catRes)) {
        $cat_name = $catRow['name'];
    }
}

// Fetch posts for category
$result = mysqli_query($conn, "
    SELECT * FROM posts 
    WHERE category_id = $id 
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Posts under <?= htmlspecialchars($cat_name) ?> category">
  <meta name="author" content="Talha Ahmed">
  <title><?= htmlspecialchars($cat_name) ?> - Talha Ahmed Blog</title>
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/css/styles.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
       <?php include './components/navbar.php'; ?>
<!-- Header -->
       <?php include './components/header.php'; ?>
<!-- Category posts -->
<main class="flex-grow-1 container">
  <div class="row">
    <div class="col-lg-8">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
          <?php while ($post = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-6">
              <div class="card mb-4 shadow-sm h-100">
                <?php if (!empty($post['image'])): ?>
                  <img class="card-img-top" 
                       src="/uploads/<?= htmlspecialchars($post['image']) ?>" 
                       alt="<?= htmlspecialchars($post['title']) ?>"
                       style="height:200px;object-fit:cover;">
                <?php endif; ?>
                <div class="card-body">
                  <div class="small text-muted"><?= date('F j, Y', strtotime($post['created_at'])) ?></div>
                  <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                  <p class="card-text"><?= substr(strip_tags($post['content']), 0, 100) ?>...</p>
                  <a href="post/<?= htmlspecialchars($post['slug']) ?>" class="btn btn-outline-primary btn-sm">
                    Read Full Article →
                  </a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="text-muted">No posts found in this category.</p>
      <?php endif; ?>
    </div>
  


      <!-- Search widget -->
             <?php include './components/widgets.php'; ?>
</div>
</main>
<!-- Footer -->
       <?php include './components/footer.php'; ?>
        
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
