<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/admin/authenticateadmin.php"); ?>




<?php

    extract($_POST);
    $addedsuccessfully="";
    $restaurantexists="";
    if(isset($addrestaurant)){
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM restaurants WHERE name=?");
            $prepq->execute(array($name));
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        if( $prepq->rowCount()==1 ){
            $restaurantexists="<div style='color:red; text-align:center; font-size: 12px;'>Email is already associated with an account.</div>";
        }
        else {
            try {
                require($phppath.'callable/connection.php');
                $prep=$db->prepare("INSERT INTO restaurants (name, logo, description) VALUES(?, 'rli/default.png', ?)");
                $prep->execute(array($name, $description));
                $insertedid=$db->lastinsertid();
                $db=null;

            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 1000000000)
            && in_array($extension, $allowedExts))
              {
              if ($_FILES["file"]["error"] > 0)
                {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
                }
              else {
                  $fileName = $temp[0].".".$temp[1];
                  $temp[0] = rand(0, 3000); //Set to random number
                  $fileName;
                      $temp = explode(".", $_FILES["file"]["name"]);
                      $newfilename = $insertedid . '.' . end($temp);
                      move_uploaded_file($_FILES["file"]["tmp_name"], $phppath."rli/" . $newfilename);
                      try {
                          require($phppath.'callable/connection.php');
                          $prepu=$db->prepare("UPDATE restaurants SET logo=? WHERE name=?");
                          $prepu->execute(array("rli/".$newfilename, $name));
                          $db=null;
                      }
                      catch (PDOException $e) {
                          echo "Error occured!";
                          die($e->getMessage());
                      }
                  }
                }
            $addedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>$name has been added succesfully.</div>";
            $name="";
            $logo="";
            $description="";
        }
    }
    else {
       $name="";
       $logo="";
       $description="";
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
  <h1 class="mt-4 mb-3">Add Restaurant</h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item active">Add Restaurant</li>
  </ol>

  <!-- /.row -->

  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
      <?php echo "$addedsuccessfully"; ?>
      <form method="POST" enctype="multipart/form-data">
        <?php echo "$restaurantexists"; ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Name:</label>
            <input value='<?php echo $name; ?>' type="text" class="form-control" id="name" name="name" required data-validation-required-message="Please enter restaurant name.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Description:</label>
            <input value='<?php echo $description; ?>' type="text" class="form-control" id="description" name="description" required data-validation-required-message="Please enter restaurant description.">
          </div>
        </div>
        <div class="control-group form-group">
            <div style="text-align:center;" class="controls">
                <input style="width:75%;" class="btn btn-primary" type="file" name="file" id="fileToUpload">
            </div>
        </div>
        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="addrestaurant" id="addrestaurant">Add Restaurant</button>
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
