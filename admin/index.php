<?php
include 'auth.php'; // make sure only logged-in admins can access
include '../db.php';
include 'header.php';

// Get counts
$postCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts"))['total'];
$categoryCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"))['total'];
$tagCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT name) AS total FROM tags"))['total'];
$commentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM comments"))['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/styles.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>

<body>
    <main class="container mt-5 main-container">
        <h2 class="mb-4">Admin Dashboard</h2>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Posts</h5>
                        <p class="card-text"><?= $postCount ?> total posts</p>
                        <a href="/admin/manage.php" class="btn btn-light btn-sm">Manage Posts</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Categories</h5>
                        <p class="card-text"><?= $categoryCount ?> categories</p>
                        <a href="/admin/categories.php" class="btn btn-light btn-sm">Manage Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Tags</h5>
                        <p class="card-text"><?= $tagCount ?> unique tags</p>
                        <a href="/admin/tags.php" class="btn btn-light btn-sm">View Tags</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Comments</h5>
                        <p class="card-text"><?= $commentCount ?> comments</p>
                        <a href="/admin/comments.php" class="btn btn-light btn-sm">Moderate Comments</a>
                    </div>
                </div>
            </div>
        </div>

        <a href="/admin/add.php" class="btn btn-primary">+ Add New Post</a>
        <a href="../index.php" class="btn btn-secondary">← Go to Site</a>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>