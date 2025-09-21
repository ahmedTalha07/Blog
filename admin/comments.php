<?php
include 'auth.php';
include '../db.php';
include 'header.php';

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM comments WHERE id = $id");
    header("Location: comments.php?deleted=1");
    exit;
}

// Fetch all comments with related post title
$comments = mysqli_query($conn, "
    SELECT comments.*, posts.title AS post_title, posts.slug AS post_slug
    FROM comments
    JOIN posts ON comments.post_id = posts.id
    ORDER BY comments.created_at DESC
");
?>
<head>
    <link href="../css/styles.css" rel="stylesheet">
</head>

<main class="container main-container mt-5">
    <h3>All Comments</h3>
    <hr>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-warning">Comment deleted.</div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($comments) > 0): ?>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Comment</th>
                    <th>Post</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = mysqli_fetch_assoc($comments)): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars(substr($c['content'], 0, 100)) ?>...</td>
                        <td>
                            <a href="../post/<?= $c['post_slug'] ?>" target="_blank">
                                <?= htmlspecialchars($c['post_title']) ?>
                            </a>
                        </td>
                        <td><small><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></small></td>
                        <td>
                            <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
    </main>

<?php include 'footer.php'; ?>
