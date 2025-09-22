<?php
$current_page = basename($_SERVER['PHP_SELF']); // e.g. "index.php"
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/index">Talha Ahmed</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="../index.php">Blog</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" href="../about.php">About</a>
        </li>
      </ul>
    </div>
  </div>
</nav>