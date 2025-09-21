<?php
include 'auth.php';
include '../db.php';
include 'header.php';

// Handle tag update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name) {
        mysqli_query($conn, "UPDATE tags SET name = '$name' WHERE id = $id");
        header("Location: tags.php?updated=1");
        exit;
    }
}

// Handle tag delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // First remove from post_tags to maintain foreign key integrity
    mysqli_query($conn, "DELETE FROM post_tags WHERE tag_id = $id");
    mysqli_query($conn, "DELETE FROM tags WHERE id = $id");
    header("Location: tags.php?deleted=1");
    exit;
}

// Determine if editing
$editId = isset($_GET['edit']) ? intval($_GET['edit']) : null;

// Fetch all tags with post count
$tags = mysqli_query($conn, "
    SELECT tags.id, tags.name, COUNT(post_tags.post_id) AS post_count
    FROM tags
    LEFT JOIN post_tags ON tags.id = post_tags.tag_id
    GROUP BY tags.id, tags.name
    ORDER BY post_count DESC, tags.name
");
?>
<head>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<main class="container main-container mt-5">
    <h3>Manage Tags <span class="badge bg-info"><?= mysqli_num_rows($tags) ?> total</span></h3>
    <hr>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-info">Tag updated.</div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="alert alert-warning">Tag deleted.</div>
    <?php endif; ?>

    <table class="table table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Tag</th>
                <th>Linked Posts</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($tag = mysqli_fetch_assoc($tags)): ?>
                <tr>
                    <td>
                        <?php if ($editId === (int)$tag['id']): ?>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="id" value="<?= $tag['id'] ?>">
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($tag['name']) ?>" required>
                                <button type="submit" name="update" class="btn btn-success btn-sm">Save</button>
                            </form>
                        <?php else: ?>
                            <a href="tagged_posts.php?id=<?= $tag['id'] ?>" class="badge bg-secondary fs-6 text-decoration-none">
                                <?= htmlspecialchars($tag['name']) ?>
                            </a>

                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-dark"><?= $tag['post_count'] ?></span></td>
                    <td>
                        <?php if ($editId !== (int)$tag['id']): ?>
                            <a href="?edit=<?= $tag['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="?delete=<?= $tag['id'] ?>" onclick="return confirm('Delete this tag?')" class="btn btn-sm btn-danger">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php include 'footer.php'; ?>