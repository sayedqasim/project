<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<?php
    extract($_POST);
    $addedsuccessfully="";
    $branchexists="";
    $invalidphone="";
    if(isset($addbranch)){
        $address = $block . ':' . $road . ':' . $building;
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM branches WHERE address=? AND restaurantid=?");
            $prepq->execute(array($address,$_SESSION['restaurantid']));
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        $pattern="/^((00)?(973))?[369178][0-9]{7}$/";
        if(!preg_match($pattern, $phone) || $prepq->rowCount()==1 ){
            if (!preg_match($pattern, $phone))
                $invalidphone="<div style='color:red; text-align:center; font-size: 12px;'>Please enter a valid phone number.</div>";
            if ($prepq->rowCount()==1)
                $branchexists="<div style='color:red; text-align:center; font-size: 12px;'>Branch already exists.</div>";
        }
        else {
            try {
                require($phppath.'callable/connection.php');
                $db->beginTransaction();
                $prep=$db->prepare("INSERT INTO branches (restaurantid, area, address, phone) VALUES(?, ?, ?, ?)");
                $prep->execute(array($_SESSION['restaurantid'], $area, $address, $phone));
                $prepn=$db->prepare("SELECT name, logo FROM restaurants WHERE restaurantid=?");
                $prepn->execute(array($_SESSION['restaurantid']));
                $rown=$prepn->fetch(PDO::FETCH_ASSOC);
                $nameofuser=$rown['name'].' '.$area;
                $emailofuser=$string = str_replace(' ', '', strtolower($rown['name'].'-'.$area)."@email.com");
                $passwordofuser=str_replace(' ', '', strtolower($rown['name'].$area)."pass");
                $prepa=$db->prepare("INSERT INTO users (name, email, password, phone, profilepicture, usertype) VALUES (?, ?, MD5(?), ?, ?,'branch')");
                $prepa->execute(array($nameofuser, $emailofuser, $passwordofuser, $phone, $rown['logo']));
                $db->commit();
                $db=null;
            } catch (PDOException $e) {
                $db->rollBack();
                echo "Error occured!";
                die($e->getMessage());
            }
            $addedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Branch has been added succesfully.</div>";
            $area ="";
            $block ="";
            $road ="";
            $building="";
            $phone="";
        }
    }
    else {
        $area ="";
        $block ="";
        $road ="";
        $building="";
        $phone="";
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
  <h1 class="mt-4 mb-3">Add Branch</h1>

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
    <li class="breadcrumb-item active">Add Branch</li>
  </ol>

  <!-- /.row -->
  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
      <?php echo "$addedsuccessfully"; ?>
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
            <input value='<?php echo $phone; ?>' type="text" class="form-control" id="phone" name="phone" required data-validation-required-message="Please enter branch phone.">
          </div>
        </div>
        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="addbranch" id="addbranch">Add Branch</button>
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
