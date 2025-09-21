<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="About Talha Ahmed – Full-Stack Web Developer & Football Fanatic.">
  <meta name="author" content="Talha Ahmed">
  <title>About – Talha Ahmed</title>
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  <link href="css/styles.css" rel="stylesheet">
  <!-- Font Awesome for social icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* make the about text feel bigger & nicer */
    .about-body p {
      font-size: 1.2rem;
      line-height: 1.8;
      margin-bottom: 1.2rem;
    }
    .about-body h2 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-top: 2rem;
    }
    /* classy black icons */
    .social-links a {
      color: #000;
      transition: color 0.2s ease;
    }
    .social-links a:hover {
      color: #555; /* subtle hover */
    }
    .social-links i {
      font-size: 1.8rem;
    }
  </style>
</head>

<body>
<?php include './components/navbar.php'; ?>

<!-- Page header -->
       <?php include './components/header.php'; ?>

<!-- Page content -->
<div class="container">
  <div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
      <div class="card mb-4 shadow-sm">
        <div class="card-body about-body">
          <p>
            Hello there, fellow wanderer — my name is <strong>Talha Ahmed</strong>.
            I’m a <strong>Professional Full-Stack Web Developer</strong> and lifelong football fanatic
            (with Manchester United and coding being my two biggest obsessions).
          </p>

          <p>
            I build fast, scalable, and user-friendly websites and web apps for small businesses and startups,
            focusing on clean code, intuitive design, and long-term client relationships.
          </p>

          <p>
            When I’m not coding, you’ll probably find me dissecting the latest United match
            or experimenting with new tech tools.
            I’m always open to collaborations, new projects, or just a chat about football or code.
          </p>

          <h2>Connect with Me</h2>
          <ul class="list-inline mt-3 social-links">
            <li class="list-inline-item">
              <a href="YOUR_UPWORK_LINK" target="_blank" aria-label="Upwork"><i class="fa-brands fa-upwork"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="YOUR_TWITTER_LINK" target="_blank" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="YOUR_FACEBOOK_LINK" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
            </li>
            <li class="list-inline-item">
              <a href="YOUR_LINKEDIN_LINK" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Sidebar widgets -->
       <?php include './components/widgets.php'; ?>
<!-- Footer -->
<footer class="py-5 bg-dark">
  <div class="container">
    <p class="m-0 text-center text-white">
      &copy; <?= date('Y') ?> Talha Ahmed – All Rights Reserved
    </p>
  </div>
</footer>

<!-- Bootstrap core JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
