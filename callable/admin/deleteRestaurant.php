<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

<?php
    require($phppath."callable/admin/authenticateadmin.php");
    extract($_POST);
    if (isset($restaurantid)) {
        $_SESSION['restaurantid']=$restaurantid;
    }
    if (isset($delete)) {
        try {
            require($phppath.'callable/connection.php');
            $prepd=$db->prepare("DELETE FROM restaurants WHERE restaurantid=?");
            $prepd->execute(array($_SESSION['restaurantid']));
            $db=null;
            header('location:'.$htmlpath.'callable/admin/restaurantview.php');
            die();
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
    }
    try {
        require($phppath.'callable/connection.php');
        $prepd=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
        $prepd->execute(array($_SESSION['restaurantid']));
        $db=null;
        $row=$prepd->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }

?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<body>

<!-- Navigation -->
<?php require($navigation); ?>

<!-- Profile Form -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">Delete Restaurant</h1>

<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
  </li>
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'callable/admin/restaurantview.php';?>">Restaurants</a>
  </li>
  <li class="breadcrumb-item active">Delete Restaurant</li>
</ol>
      <div style='color:red; text-align:center; font-size: 12px;'>Are you sure you want to delete <?php echo $row['name']; ?>?</div>
      <br/>
      <div class='row' >
          <div style='text-align:center;' class='col-md-2' >
              <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''>
          </div>
          <div class='col-md-8' >
              <table >
                  <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                  <tr><td><b>Description:</b></td><td><?php echo $row['description']; ?></td></tr>
              </table>
          </div>
          <div style='text-align:center;' class='col-md-2' >
              <form method="POST">
                  <button style="margin-top:5px;width:75%" class='btn btn-primary btn-danger' name='delete' type='submit'>Delete</button>
              </form>
              <br/>
          </div>
      </div>
  </div>
  <br/>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>


</body>

</html>
