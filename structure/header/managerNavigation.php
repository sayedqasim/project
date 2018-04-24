<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href='<?php echo $htmlpath.'index.php' ?>'>Food Ordering</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
          <?php
                require($phppath.'callable/generateNavElements.php');
                $arrayOfElements= array(
                    'Restaurants' => 'callable/manager/restaurants.php',
                    'Contact Us' => 'callable/contactus.php',
                    'Edit Profile' => 'callable/editprofile.php',
                    'Logout' => 'callable/logout.php',
                );
                generateNavElements($arrayOfElements,$htmlpath);
          ?>
      </ul>
    </div>
  </div>
</nav>
