<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

<?php
    require($phppath."callable/admin/authenticateadmin.php");
    extract($_POST);
    if (isset($restaurantid))
        $_SESSION['restaurantid']=$restaurantid;
    try {
        require($phppath.'callable/connection.php');
        $prepq=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
        $prepq->execute(array($_SESSION['restaurantid']));
        $db=null;
        $rowq=$prepq->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
    if (isset($update)) {
        try {
            require($phppath.'callable/connection.php');
            $prepu=$db->prepare("UPDATE restaurants SET name=?, description=? WHERE restaurantid=?");
            $prepu->execute(array($name,$description,$rowq['restaurantid']));
            $rowq['name']=$name;
            $rowq['description']=$description;
            $db=null;
        }
        catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
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
                    $newfilename = $rowq['restaurantid'] . '.' . end($temp);
                    move_uploaded_file($_FILES["file"]["tmp_name"], $phppath."rli/" . $newfilename);
                    try {
                        require($phppath.'callable/connection.php');
                        $prepu=$db->prepare("UPDATE restaurants SET logo=? WHERE restaurantid=?");
                        $prepu->execute(array("rli/".$newfilename, $rowq['restaurantid']));
                        $rowq['logo']="rli/".$newfilename;
                        $db=null;
                    }
                    catch (PDOException $e) {
                        echo "Error occured!";
                        die($e->getMessage());
                    }
                }
              }
          }
    if (isset($resetphoto)) {
        if ($rowq['logo']!='rli/default.png') {
            try {
                require($phppath.'callable/connection.php');
                $prepr=$db->prepare("UPDATE restaurants SET logo=? WHERE restaurantid=?");
                $prepr->execute(array('rli/default.png',$rowq['restaurantid']));
                $db=null;
            }
            catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            $pic=$phppath.$rowq['logo'];
            unlink("$pic");
            $rowq['logo']="rli/default.png";
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

<!-- Profile Form -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
<h1 class="mt-4 mb-3">Edit Restaurant</h1>

<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
  </li>
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'callable/admin/restaurantview.php';?>">Restaurants</a>
  </li>
  <li class="breadcrumb-item active">Edit Restaurant</li>
</ol>


<div class="row">
  <div style="margin: auto;" class="col-lg-8 mb-4">
      <div style="text-align:center;">
          <img src="<?php echo $htmlpath.$rowq['logo'];?>" width='150' height='150'></img>
      </div>
      <div style="text-align:center;">
          <form method="POST">
              <button type="submit" name="resetphoto" class="btn btn-primary">Reset Logo</button>
          </form>
      </div>
    <form method="POST">
      <div class="control-group form-group">
        <div class="controls">
          <label>Name:</label>
          <input value='<?php echo $rowq['name']; ?>' type="text" class="form-control" id="name" name="name" required data-validation-required-message="Please enter restaurant name.">

        </div>
      </div>
      <div class="control-group form-group">
        <div class="controls">
          <label>Description:</label>
          <input value='<?php echo $rowq['description']; ?>' type="text" class="form-control" id="description" name="description" required data-validation-required-message="Please enter restaurant description.">
        </div>
      </div>


      <div style="text-align:center;">
          <button style="width:75%;" type="submit" class="btn btn-primary" name="update" id="update">Update</button>
      </div>
    </form>
    <br/>
    <div style="text-align:center;" class="container">
        <form method="post" enctype="multipart/form-data">
            Update Logo:
            <input class="btn btn-primary" type="file" name="file" id="fileToUpload">
            <button class="btn btn-primary" type="submit" value="Upload Image" name="upload">Upload</button>
        </form>
    </div>
  </div>

</div>
</div>
<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>


</body>

</html>
