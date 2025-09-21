<?php include '../db.php';
include 'auth.php';
include 'header.php';


if (!isset($_GET['id'])) {
  die("No post ID provided.");
}
$id = intval($_GET['id']);
// Fetch existing tags for this post
$tagResult = mysqli_query($conn, "
    SELECT tags.name FROM tags
    JOIN post_tags ON tags.id = post_tags.tag_id
    WHERE post_tags.post_id = $id
");

$tagArray = [];
while ($row = mysqli_fetch_assoc($tagResult)) {
    $tagArray[] = $row['name'];
}

// Convert array to comma-separated string
$tagString = implode(', ', $tagArray);
$result = mysqli_query($conn, "SELECT * FROM posts WHERE id = $id LIMIT 1");
if (!$post = mysqli_fetch_assoc($result)) {
  die("Post not found.");
}
$catResult = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Post</title>
  <link href="../css/styles.css" rel="stylesheet">
</head>

<body>
  <main class="container main-container mt-5">
    <h2>Edit Post</h2>
    <form action="update.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select class="form-control" name="category_id" required>
          <option value="">Select a category</option>
          <?php while ($cat = mysqli_fetch_assoc($catResult)): ?>
            <option value="<?= $cat['id'] ?>"
              <?= ($cat['id'] == $post['category_id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
        <label class="form-label">Title</label>
        <input class="form-control" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="tags" class="form-label">Tags (comma-separated)</label>
        <input type="text" name="tags" id="tags" class="form-control"
          value="<?= htmlspecialchars($tagString ?? '') ?>"
          placeholder="e.g. php, mysql, backend">
      </div>

      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea class="form-control" name="content" rows="7" required><?= htmlspecialchars($post['content']) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Replace Image (optional)</label>
        <input class="form-control" type="file" name="image">
        <div class="small text-muted mt-1">Current: <?= $post['image'] ?: 'No image' ?></div>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featuredCheck"
          <?= $post['featured'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="featuredCheck">Mark as Featured</label>
      </div>
      <button class="btn btn-primary" type="submit">Update</button>
      <a href="manage.php" class="btn btn-secondary">Back</a>
    </form>
          </main>
  <?php include 'footer.php'; ?>
</body>
</html>