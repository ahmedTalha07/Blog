<?php
include 'auth.php';
include '../db.php';
include 'header.php';

$tag_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$tag_q = mysqli_query($conn, "SELECT name FROM tags WHERE id = $tag_id");
$tag_data = mysqli_fetch_assoc($tag_q);
$tag_name = $tag_data ? $tag_data['name'] : 'Unknown Tag';

$posts = mysqli_query($conn, "
    SELECT posts.* FROM posts
    JOIN post_tags ON posts.id = post_tags.post_id
    WHERE post_tags.tag_id = $tag_id
    ORDER BY posts.created_at DESC
");
?>

<main class="container main-container mt-5">
    <h3>Posts with Tag: <span class="badge bg-primary"><?= htmlspecialchars($tag_name) ?></span></h3>
    <hr>

    <?php if (mysqli_num_rows($posts) > 0): ?>
        <ul class="list-group">
            <?php while ($post = mysqli_fetch_assoc($posts)): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="../post/<?= $post['slug'] ?>" target="_blank"><?= htmlspecialchars($post['title']) ?></a>
                    <small class="text-muted"><?= date('M d, Y', strtotime($post['created_at'])) ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No posts found for this tag.</p>
    <?php endif; ?>
    </main>

<?php include 'footer.php'; ?>
