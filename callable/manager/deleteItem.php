<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<?php
    extract($_POST);
    if (isset($itemid))
        $_SESSION['itemid']=$itemid;
    if (isset($delete)) {
        try {
            require($phppath.'callable/connection.php');
            $prepd=$db->prepare("DELETE FROM items WHERE itemid=?");
            $prepd->execute(array($_SESSION['itemid']));
            $db=null;
            header('location:'.$htmlpath.'callable/manager/itemview.php');
            die();
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
    }
    try {
        require($phppath.'callable/connection.php');
        $prepq=$db->prepare("SELECT * FROM items WHERE itemid=?");
        $prepq->execute(array($_SESSION['itemid']));
        $rowq=$prepq->fetch(PDO::FETCH_ASSOC);
        $db=null;
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

<!-- Page Content -->
<div class="container">

  <!-- Page Heading/Breadcrumbs -->
  <h1 class="mt-4 mb-3">Delete Item</h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'callable/manager/restaurants.php';?>">Restaurants</a>
    </li>
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'callable/manager/itemview.php';?>">Items</a>
    </li>
    <li class="breadcrumb-item active">Delete Item</li>
  </ol>

  <div class='row'>
      <div class='col-md-2' >
          <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$rowq['image']; ?>" alt=''>
      </div>
      <div class='col-md-7' >
          <table>
              <tr><td><b>Title:</b></td><td><?php echo $rowq['title']; ?></td></tr>
              <tr><td><b>Description:</b></td><td><?php echo $rowq['description']; ?></td></tr>
              <tr><td><b>Price:</b></td><td><?php echo $rowq['price']; ?> BHD</td></tr>
              <tr><td><b>Type:</b></td><td><?php echo $rowq['type']; ?></td></tr>
          </table>
      </div>
      <div style='text-align:right;' class='col-md-3' >
          <form method="POST">

              <button style="margin-top:5px;" class='btn btn-primary btn-danger' type='submit' name='delete'>Delete</button>
          </form>
      </div>
  </div>


  <br/>
  <!-- /.row -->

</div>
<!-- /.container -->


<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
