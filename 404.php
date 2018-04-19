<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modern Business - Start Bootstrap Template</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
    <?php require('userNavigation.php'); ?>

    <!-- Page Content -->
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <h1 class="mt-4 mb-3">404
        <small>Page Not Found</small>
      </h1>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
        </li>
        <li class="breadcrumb-item active">404</li>
      </ol>

      <div class="jumbotron">
        <h1 class="display-1">404</h1>
        <p>The page you're looking for could not be found. Here are some helpful links to get you back on track:</p>
        <ul>
          <li>
            <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
          </li>
          <li>
            <a href="about.php">About</a>
          </li>
          <li>
            <a href="services.php">Services</a>
          </li>
          <li>
            <a href="contact.php">Contact</a>
          </li>
          <li>
            Portfolio
            <ul>
              <li>
                <a href="portfolio-1-col.php">1 Column Portfolio</a>
              </li>
              <li>
                <a href="portfolio-2-col.php">2 Column Portfolio</a>
              </li>
              <li>
                <a href="portfolio-3-col.php">3 Column Portfolio</a>
              </li>
              <li>
                <a href="portfolio-4-col.php">4 Column Portfolio</a>
              </li>
            </ul>
          </li>
          <li>
            Blog
            <ul>
              <li>
                <a href="blog-home-1.php">Blog Home 1</a>
              </li>
              <li>
                <a href="blog-home-2.php">Blog Home 2</a>
              </li>
              <li>
                <a href="blog-post.php">Blog Post</a>
              </li>
            </ul>
          </li>
          <li>
            Other Pages
            <ul>
              <li>
                <a href="full-width-page.php">Full Width Page</a>
              </li>
              <li>
                <a href="sidebar.php">Sidebar Page</a>
              </li>
              <li>
                <a href="faq.php">FAQ</a>
              </li>
              <li>
                <a href="404.php">404 Page</a>
              </li>
              <li>
                <a href="pricing-table.php">Pricing Table</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- /.jumbotron -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <?php require('footer.php'); ?>

    <!-- Bootstrap core JavaScript -->
    <?php require($jsbs); ?>
    

  </body>

</html>
