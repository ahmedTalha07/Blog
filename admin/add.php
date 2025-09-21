<?php
include 'auth.php';
include 'header.php';
?>

<!-- admin/add.php -->
<?php include '../db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add New Post</title>
  <link href="../css/styles.css" rel="stylesheet">
</head>

<body>
  <main class="container main-container mt-5">
    <h2>Add New Blog Post</h2>
    <?php
    $catResult = mysqli_query($conn, "SELECT * FROM categories");
    ?>
    <form action="save.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <div class="mb-3">
          <label class="form-label">Category</label>
          <select class="form-control" name="category_id" required>
            <option value="">Select a category</option>
            <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="tags" class="form-label">Tags (comma-separated)</label>
          <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g. php, laravel, mysql">
        </div>


        <label class="form-label">Title</label>
        <input class="form-control" name="title" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea class="form-control" name="content" rows="7" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Featured Image</label>
        <input class="form-control" type="file" name="image">
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featuredCheck">
        <label class="form-check-label" for="featuredCheck">Mark as Featured</label>
      </div>
      <button class="btn btn-primary" type="submit">Publish</button>
      <a href="../index.php" class="btn btn-secondary">Back to Home</a>
    </form>
  </main>

  <?php include 'footer.php'; ?>

</body>
</html>