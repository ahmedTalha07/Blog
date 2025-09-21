<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - My Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (optional icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">🛠 Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage.php">Posts</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a>
                    <li class="nav-item"><a class="nav-link" href="tags.php">Tags</a></li>
                    <li class="nav-item"><a class="nav-link" href="comments.php">Comments</a></li>
                </ul>
                <span class="navbar-text me-3 text-white">
                    Logged in as <strong>Admin</strong>
                </span>
                <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>