<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<?php
    $updatedsuccessfully="";
    extract($_POST);
    if (isset($itemid))
        $_SESSION['itemid']=$itemid;
    if (isset($update)) {
        try {
            require($phppath.'callable/connection.php');
            $prep=$db->prepare("UPDATE items SET title=?,description=?,price=?,type=? WHERE itemid=?");
            $prep->execute(array($title,$description,$price,$type,$_SESSION['itemid']));
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        $updatedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Item has been updated succesfully.</div>";
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
    if (isset($upload)) {
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
                  $newfilename = $rowq['itemid'] . '.' . end($temp);
                  if (!file_exists($phppath.'rii/'.$_SESSION['restaurantid'])) {
                      mkdir($phppath.'rii/'.$_SESSION['restaurantid'], 0777, true);
                  }
                  move_uploaded_file($_FILES["file"]["tmp_name"], $phppath."rii/" . $_SESSION['restaurantid'] . '/' . $newfilename);
                  try {
                      require($phppath.'callable/connection.php');
                      $prepu=$db->prepare("UPDATE items SET image=? WHERE itemid=?");
                      $prepu->execute(array("rii/" . $_SESSION['restaurantid'] . '/' . $newfilename, $rowq['itemid']));
                      $rowq['image']="rii/" . $_SESSION['restaurantid'] . '/' . $newfilename;
                      $db=null;
                  }
                  catch (PDOException $e) {
                      echo "Error occured!";
                      die($e->getMessage());
                  }
                  $updatedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Item has been updated succesfully.</div>";
              }
            }
        }
        if (isset($reset)) {
            if ($rowq['image']!='rii/default.png') {
                try {
                    require($phppath.'callable/connection.php');
                    $prepr=$db->prepare("UPDATE items SET image=? WHERE itemid=?");
                    $prepr->execute(array('rii/default.png',$_SESSION['itemid']));
                    $db=null;
                }
                catch (PDOException $e) {
                    echo "Error occured!";
                    die($e->getMessage());
                }
                $pic=$phppath.$rowq['image'];
                unlink("$pic");
                $rowq['image']="rii/default.png";
                $updatedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>Item has been updated succesfully.</div>";
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
  <h1 class="mt-4 mb-3">Edit Item</h1>

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
    <li class="breadcrumb-item active">Edit Item</li>
  </ol>

  <!-- /.row -->
  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
        <?php echo "$updatedsuccessfully"; ?>
        <div style="text-align:center;">
            <img src="<?php echo $htmlpath.$rowq['image'];?>" width='150' height='150'></img>
        </div>
        <div style="text-align:center;">
            <form method="POST">
                <button type="submit" name="reset" class="btn btn-primary">Reset Image</button>
            </form>
        </div>
      <form method="POST">
        <div class="control-group form-group">
          <div class="controls">
            <label>Title:</label>
            <input value='<?php echo $rowq['title']; ?>' type="text" class="form-control" id="title" name="title" required data-validation-required-message="Please enter item title.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Description:</label>
            <input value='<?php echo $rowq['description']; ?>' type="text" class="form-control" id="description" name="description" required data-validation-required-message="Please enter item description.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Price:</label>
            <input value='<?php echo $rowq['price']; ?>' type="text" class="form-control" id="price" name="price" required data-validation-required-message="Please enter item price.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Type:</label>
            <input value='<?php echo $rowq['type']; ?>' type="text" class="form-control" id="type" name="type" required data-validation-required-message="Please enter item type.">
          </div>
        </div>
        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="update" id="update">Update Item</button>
        </div>
      </form>
    </div>
  </div>
  <div style="text-align:center;" class="container">
      <form method="post" enctype="multipart/form-data">
          Update Image:
          <input class="btn btn-primary" type="file" name="file" id="fileToUpload">
          <button class="btn btn-primary" type="submit" value="Upload Image" name="upload">Upload</button>
      </form>
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
