<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<?php
    extract($_POST);
    $updatedsuccessfully="";
    $branchexists="";
    $invalidphone="";
    if (isset($branchid))
        $_SESSION['branchid']=$branchid;
    if(isset($update)){
        $address = $area . ':' . $block . ':' . $road . ':' . $building;
        $pattern="/^((00)?(973))?[369178][0-9]{7}$/";
        if(!preg_match($pattern, $phone))
            $invalidphone="<div style='color:red; text-align:center; font-size: 12px;'>Please enter a valid phone number.</div>";

        else {
            try {
                require($phppath.'callable/connection.php');
                $prep=$db->prepare("UPDATE branches SET address=?, phone=? WHERE branchid=?");
                $prep->execute(array($address, $phone, $_SESSION['branchid']));
                $db=null;
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            $updatedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Branch has been updated succesfully.</div>";
            $area ="";
            $block ="";
            $road ="";
            $building="";
            $phone="";
        }
    }
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
    $area=$explodedaddress[0];
    $block=$explodedaddress[1];
    $road=$explodedaddress[2];
    $building=$explodedaddress[3];
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
  <h1 class="mt-4 mb-3">Edit Branch</h1>

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
    <li class="breadcrumb-item active">Edit Branch</li>
  </ol>

  <!-- /.row -->
  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
      <?php echo "$updatedsuccessfully"; ?>
      <form method="POST">
        <?php echo "$branchexists"; ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Area:</label>
            <input value='<?php echo $area; ?>' type="text" class="form-control" id="area" name="area" required data-validation-required-message="Please enter branch area.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Block:</label>
            <input value='<?php echo $block; ?>' type="text" class="form-control" id="block" name="block" required data-validation-required-message="Please enter branch block.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Road:</label>
            <input value='<?php echo $road; ?>' type="text" class="form-control" id="road" name="road" required data-validation-required-message="Please enter branch road.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Building:</label>
            <input value='<?php echo $building; ?>' type="text" class="form-control" id="building" name="building" required data-validation-required-message="Please enter branch building.">
          </div>
        </div>
        <?php echo $invalidphone ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Phone:</label>
            <input value="<?php echo $rowq['phone']; ?>" type="text" class="form-control" id="phone" name="phone" required data-validation-required-message="Please enter branch phone.">
          </div>
        </div>
        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="update" id="update">Update Branch</button>
        </div>
      </form>
    </div>

  </div>
  <!-- /.row -->

</div>
<!-- /.container -->


<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
