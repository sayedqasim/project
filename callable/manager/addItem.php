<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<?php
    extract($_POST);
    $addedsuccessfully="";
    $itemexists="";
    if(isset($additem)){
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM items WHERE restaurantid=? AND title=?");
            $prepq->execute(array($title,$_SESSION['restaurantid']));
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        if ($prepq->rowCount()==1)
            $itemexists="<div style='color:red; text-align:center; font-size: 12px;'>Item already exists.</div>";
        else {
            try {
                require($phppath.'callable/connection.php');
                $prep=$db->prepare("INSERT INTO items (restaurantid,title,description,price,type) VALUES(?, ?, ?, ?, ?)");
                $prep->execute(array($_SESSION['restaurantid'],$title,$description,$price,$type));
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
                      if (!file_exists($phppath.'rii/'.$_SESSION['restaurantid'])) {
                          mkdir($phppath.'rii/'.$_SESSION['restaurantid'], 0777, true);
                      }
                      move_uploaded_file($_FILES["file"]["tmp_name"], $phppath."rii/" . $_SESSION['restaurantid'] . '/' . $newfilename);
                      try {
                          require($phppath.'callable/connection.php');
                          $prepu=$db->prepare("UPDATE items SET image=? WHERE itemid=?");
                          $prepu->execute(array("rii/" . $_SESSION['restaurantid'] . '/' . $newfilename, $insertedid));
                          $db=null;
                      }
                      catch (PDOException $e) {
                          echo "Error occured!";
                          die($e->getMessage());
                      }
                  }
                }
            $addedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Item has been added succesfully.</div>";
            $title="";
            $description="";
            $price="";
            $type="";
        }
    }
    else {
        $title="";
        $description="";
        $price="";
        $image="";
        $type="";
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
  <h1 class="mt-4 mb-3">Add Item</h1>

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
    <li class="breadcrumb-item active">Add Item</li>
  </ol>

  <!-- /.row -->
  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
      <?php echo "$addedsuccessfully"; ?>
      <form method="POST" enctype="multipart/form-data">
        <?php echo "$itemexists"; ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Title:</label>
            <input value='<?php echo $title; ?>' type="text" class="form-control" id="title" name="title" required data-validation-required-message="Please enter item title.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Description:</label>
            <input value='<?php echo $description; ?>' type="text" class="form-control" id="description" name="description" required data-validation-required-message="Please enter item description.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Price:</label>
            <input value='<?php echo $price; ?>' type="text" class="form-control" id="price" name="price" required data-validation-required-message="Please enter item price.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Type:</label>
            <input value='<?php echo $type; ?>' type="text" class="form-control" id="type" name="type" required data-validation-required-message="Please enter item type.">
          </div>
        </div>
        <div class="control-group form-group">
            <div style="text-align:center;" class="controls">
                <input style="width:75%;" class="btn btn-primary" type="file" name="file" id="fileToUpload" required data-validation-required-message="Please enter item image.">
            </div>
        </div>
        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="additem" id="additem">Add Item</button>
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
