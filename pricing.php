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
      <h1 class="mt-4 mb-3">Pricing
        <small>Subheading</small>
      </h1>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
        </li>
        <li class="breadcrumb-item active">Pricing</li>
      </ol>

      <!-- Content Row -->
      <div class="row">
        <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h3 class="card-header">Basic</h3>
            <div class="card-body">
              <div class="display-4">$19.99</div>
              <div class="font-italic">per month</div>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Vestibulum at eros</li>
              <li class="list-group-item">
                <a href="#" class="btn btn-primary">Sign Up!</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="card card-outline-primary h-100">
            <h3 class="card-header bg-primary text-white">Plus</h3>
            <div class="card-body">
              <div class="display-4">$39.99</div>
              <div class="font-italic">per month</div>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Vestibulum at eros</li>
              <li class="list-group-item">
                <a href="#" class="btn btn-primary">Sign Up!</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h3 class="card-header">Ultra</h3>
            <div class="card-body">
              <div class="display-4">$159.99</div>
              <div class="font-italic">per month</div>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Cras justo odio</li>
              <li class="list-group-item">Dapibus ac facilisis in</li>
              <li class="list-group-item">Vestibulum at eros</li>
              <li class="list-group-item">
                <a href="#" class="btn btn-primary">Sign Up!</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <?php require('footer.php'); ?>

    <!-- Bootstrap core JavaScript -->
    <?php require($jsbs); ?>
    

  </body>

</html>
