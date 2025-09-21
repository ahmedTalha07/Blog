<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Talha Ahmed’s personal blog – documenting projects, tutorials, and life updates.">
    <meta name="author" content="Talha Ahmed">
    <title>Talha Ahmed Blog</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>
    <!-- Responsive navbar -->
    <?php include './components/navbar.php'; ?>

    <!-- Page header -->
    <?php include './components/header.php'; ?>
    <!-- Page content -->
    <div class="container">
        <div class="row">
            <!-- Blog entries -->
            <div class="col-lg-8">
                <?php include 'db.php'; ?>

                <!-- Featured blog post -->
                <?php
                $featuredResult = mysqli_query($conn, "
                SELECT posts.*, categories.name AS category_name 
                FROM posts 
                LEFT JOIN categories ON posts.category_id = categories.id 
                WHERE featured = 1 
                ORDER BY created_at DESC 
                LIMIT 1
            ");
                if ($featured = mysqli_fetch_assoc($featuredResult)):
                ?>
                    <div class="card mb-4 shadow-sm">
                        <img class="card-img-top"
                            src="uploads/<?= htmlspecialchars($featured['image']) ?>"
                            alt="<?= htmlspecialchars($featured['title']) ?>">
                        <div class="card-body">
                            <div class="small text-muted">
                                <?= date('F j, Y', strtotime($featured['created_at'])) ?>
                                <?php if (!empty($featured['category_name'])): ?>
                                    | <span class="badge bg-secondary"><?= htmlspecialchars($featured['category_name']) ?></span>
                                <?php endif; ?>
                                <span class="badge bg-success ms-2">Featured</span>
                            </div>
                            <h2 class="card-title"><?= htmlspecialchars($featured['title']) ?></h2>
                            <p class="card-text"><?= substr(strip_tags($featured['content']), 0, 200) ?>...</p>
                            <a class="btn btn-primary" href="post?=htmlspecialchars($featured['slug']) ?>">
                                Read Full Article →
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Non-featured posts -->
                <div class="row">
                    <?php
                    $postsPerPage = 4;
                    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                    $offset = ($page - 1) * $postsPerPage;

                    // Count total non-featured posts
                    $totalRes = mysqli_query($conn, "SELECT COUNT(*) as total FROM posts WHERE featured = 0");
                    $totalRows = mysqli_fetch_assoc($totalRes)['total'];
                    $totalPages = ceil($totalRows / $postsPerPage);

                    // Fetch current page posts
                    $nonFeatured = mysqli_query($conn, "
                    SELECT posts.*, categories.name AS category_name 
                    FROM posts 
                    LEFT JOIN categories ON posts.category_id = categories.id 
                    WHERE featured = 0 
                    ORDER BY created_at DESC 
                    LIMIT $offset, $postsPerPage
                ");

                    while ($post = mysqli_fetch_assoc($nonFeatured)):
                    ?>
                        <div class="col-lg-6">
                            <div class="card mb-4 h-100 shadow-sm">
                                <img class="card-img-top"
                                    src="uploads/<?= htmlspecialchars($post['image']) ?>"
                                    alt="<?= htmlspecialchars($post['title']) ?>">
                                <div class="card-body">
                                    <div class="small text-muted">
                                        <?= date('F j, Y', strtotime($post['created_at'])) ?>
                                        <?php if (!empty($post['category_name'])): ?>
                                            | <span class="badge bg-secondary"><?= htmlspecialchars($post['category_name']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="card-title h4"><?= htmlspecialchars($post['title']) ?></h2>
                                    <p class="card-text"><?= substr(strip_tags($post['content']), 0, 100) ?>...</p>
                                    <a class="btn btn-outline-primary" href="post/<?= htmlspecialchars($post['slug']) ?>">
                                        Read Full Article →
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php include './components/pagination.php'; ?>

            <?php include './components/widgets.php'; ?>

    <!-- Footer -->
           <?php include './components/footer.php'; ?>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="js/scripts.js"></script>
</body>

</html>