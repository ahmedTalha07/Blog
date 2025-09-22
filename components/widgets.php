<?php include $_SERVER['DOCUMENT_ROOT'] . '/db.php';
?>
<!-- Search widget -->
<div class="col-lg-4">
    <div class="card mb-4">
        <div class="card-header">Search</div>
        <div class="card-body">
            <form action="<?= htmlspecialchars($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']) ?>/search.php" method="get">
                <div class="input-group">
                    <label for="q" class="visually-hidden">Search:</label>
                    <input class="form-control" type="text" id="q" name="q" placeholder="Search..." required>
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>


        </div>
    </div>

    <!-- Categories widget -->
    <div class="card mb-4">
        <div class="card-header">Categories</div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <?php
                $cats = mysqli_query($conn, "SELECT * FROM categories");
                while ($c = mysqli_fetch_assoc($cats)):
                ?>
                    <li><a href="/category.php?id=<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['name']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>