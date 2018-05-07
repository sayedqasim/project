<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/customer/authenticatecustomer.php"); ?>

<?php
    extract($_POST);
    $addedsuccessfully="";

    if (isset($addaddress)) {
        $address = $block . ':' . $road . ':' . $building;
        try {
            require($phppath.'callable/connection.php');
            $prep=$db->prepare("INSERT INTO useraddresses (userid, area, address) VALUES((SELECT userid FROM users WHERE email=?), ?, ?)");
            $prep->execute(array($_SESSION['email'], $area, $address));
            $db=null;
        } catch (PDOException $e) {

            echo "Error occured!";
            die($e->getMessage());
        }
        $addedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Address has been added succesfully.</div>";
        $area ="";
        $block ="";
        $road ="";
        $building="";
        $phone="";
    }
    else {
        $area ="";
        $block ="";
        $road ="";
        $building="";
        $phone="";
    }
    if (isset($deleteaddress)) {
        try {
            require($phppath.'callable/connection.php');
            $prepd=$db->prepare("DELETE FROM useraddresses WHERE addressid=?");
            $prepd->execute(array($deleteaddress));
            $db=null;
        } catch (PDOException $e) {
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
  <h1 class="mt-4 mb-3">Addresses</h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item active">Addresses</li>
  </ol>

  <!-- /.row -->
  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
      <?php echo "$addedsuccessfully"; ?>
      <form method="POST">
        <div class="control-group form-group">
          <div class="controls">
            <label>Area:</label>
            <input value='<?php echo $area; ?>' type="text" class="form-control" id="area" name="area" required data-validation-required-message="Please enter address area.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Block:</label>
            <input value='<?php echo $block; ?>' type="text" class="form-control" id="block" name="block" required data-validation-required-message="Please enter address block.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Road:</label>
            <input value='<?php echo $road; ?>' type="text" class="form-control" id="road" name="road" required data-validation-required-message="Please enter address road.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Building:</label>
            <input value='<?php echo $building; ?>' type="text" class="form-control" id="building" name="building" required data-validation-required-message="Please enter address building.">
          </div>
        </div>
        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="addaddress" id="addaddress">Add Address</button>
        </div>
      </form>
    </div>

  </div>
  <!-- /.row -->
  <hr/>
  <?php
    require($phppath.'callable/connection.php');
    $prepqa=$db->prepare("SELECT * FROM useraddresses WHERE userid=(SELECT userid FROM users WHERE email=?)");
    $prepqa->execute(array($_SESSION['email']));
    $rsqa=$prepqa->fetchAll(PDO::FETCH_ASSOC);
    if (count($rsqa)<1) {
        echo "<div style='color:red; text-align:center; font-size: 12px;'>No addresses found.</div>";
    }
    foreach ($rsqa as $address) {
        ?>
        <div class="row">
                <div style="text-align: center;"class="col-md-4">
                    <?php echo $address['area'] ?>
                </div>
                <div style="text-align: center;"class="col-md-4">
                    <?php echo $address['address'] ?>
                </div>
                <div style="text-align: center;"class="col-md-4">
                    <form method="POST">
                        <button type="submit" class="btn btn-primary btn-danger" name="deleteaddress" value="<?php echo $address['addressid'] ?>" >Delete</button>
                    </form>
                </div>
        </div>
        <?php
    }
  ?>
</div>
<br/>
<!-- /.container -->

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
