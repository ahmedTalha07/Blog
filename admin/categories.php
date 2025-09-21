<?php
include 'auth.php';
include '../db.php';
include 'header.php';

// Add category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name) {
        mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
        header("Location: categories.php?success=1");
        exit;
    }
}

// Update category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    mysqli_query($conn, "UPDATE categories SET name = '$name' WHERE id = $id");
    header("Location: categories.php?updated=1");
    exit;
}

// Delete category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    header("Location: categories.php?deleted=1");
    exit;
}

$editId = isset($_GET['edit']) ? intval($_GET['edit']) : null;
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
?>
<head>
    <link href="../css/styles.css" rel="stylesheet">
</head>
<main class="container main-container mt-5">
    <h3>Manage Categories</h3>
    <hr>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Category added.</div>
    <?php elseif (isset($_GET['updated'])): ?>
        <div class="alert alert-info">Category updated.</div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="alert alert-warning">Category deleted.</div>
    <?php endif; ?>

    <!-- Add Category -->
    <form method="POST" class="mb-4 d-flex gap-2">
        <input type="text" name="name" class="form-control" placeholder="New category name" required>
        <button class="btn btn-primary" type="submit" name="add">Add Category</button>
    </form>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th style="width: 60%;">Category Name</th>
                <th style="width: 40%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <tr>
                    <td>
                        <?php if ($editId === (int)$cat['id']): ?>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" class="form-control" required>
                                <button type="submit" name="update" class="btn btn-success btn-sm">Save</button>
                            </form>
                        <?php else: ?>
                            <?= htmlspecialchars($cat['name']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($editId !== (int)$cat['id']): ?>
                            <a href="?edit=<?= $cat['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="?delete=<?= $cat['id'] ?>" onclick="return confirm('Delete this category?')" class="btn btn-sm btn-danger">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </main>

    <?php include 'footer.php'; ?>