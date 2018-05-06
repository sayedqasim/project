<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<?php
    extract($_POST);
    if (isset($branchid))
        $_SESSION['branchid']=$branchid;
    try {
        require($phppath.'callable/connection.php');
        $prepq=$db->prepare("SELECT * FROM branches WHERE branchid=?");
        $prepq->execute(array($_SESSION['branchid']));
        $rowq=$prepq->fetch(PDO::FETCH_ASSOC);
        $db=null;
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
    $explodedaddress=explode(':', $rowq['address']);
    $block=$explodedaddress[0];
    $road=$explodedaddress[1];
    $building=$explodedaddress[2];
    $rowq['address'] = $rowq['area'] . ', Block: ' . $block . ', Road: ' . $road . ', Building: ' . $building . '.';
    if (isset($delete)) {
        try {
            require($phppath.'callable/connection.php');
            $db->beginTransaction();
            $prepd=$db->prepare("DELETE FROM branches WHERE branchid=?");
            $prepd->execute(array($_SESSION['branchid']));
            $prepn=$db->prepare("SELECT name FROM restaurants WHERE restaurantid=?");
            $prepn->execute(array($_SESSION['restaurantid']));
            $rown=$prepn->fetch(PDO::FETCH_ASSOC);
            $emailofuser=strtolower($rown['name'].'-'.$rowq['area'])."@email.com";
            $prepda=$db->prepare("DELETE FROM users WHERE email=?");
            $prepda->execute(array($emailofuser));
            $db->commit();
            $db=null;
            header('location:'.$htmlpath.'callable/manager/branchview.php');
            die();
        } catch (PDOException $e) {
            $db->rollBack();
            echo "Error occured!";
            die($e->getMessage());
        }
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
  <h1 class="mt-4 mb-3">Delete Branch</h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'callable/manager/restaurants.php';?>">Restaurants</a>
    </li>
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'callable/manager/branchview.php';?>">Branches</a>
    </li>
    <li class="breadcrumb-item active">Delete Branch</li>
  </ol>

  <!-- /.row -->
  <div class="row">

          <div class='col-md-9' >
              <table>
                  <tr><td><b>Address:</b></td><td><?php echo $rowq['address']; ?></td></tr>
                  <tr><td><b>Phone:</b></td><td><?php echo $rowq['phone']; ?></td></tr>
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
