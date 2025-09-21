<?php
include 'auth.php';
include 'header.php';
?>

<?php include '../db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Posts</title>
  <link href="../css/styles.css" rel="stylesheet">
</head>

<body>
  <main class="container mt-5 main-container">
    <h2>Manage Blog Posts</h2>
    <a href="logout.php" class="btn btn-outline-danger mb-3 float-end">Logout</a>
    <a href="add.php" class="btn btn-success mb-3">+ Add New Post</a>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Title</th>
          <th>Featured</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM posts ORDER BY created_at DESC");
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= $row['featured'] ? '✅ Yes' : '—' ?></td>
            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
            <td>
              <?php if (!$row['featured']): ?>
                <a href="feature.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary mb-1">Set as Featured</a>
              <?php else: ?>
                <span class="text-muted d-block mb-1">Currently Featured</span>
              <?php endif; ?>

              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
            </td>

          </tr>
        <?php } ?>
      </tbody>
    </table>
              </main>
  <?php include 'footer.php'; ?>
</body>
</html>