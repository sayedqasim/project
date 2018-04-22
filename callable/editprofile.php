    <!-- Modular Require -->
    <?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

    <?php
        if (!isset($_SESSION['email'])) {
            header('location:login.php');
            die();
        }
        extract($_POST);
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM users WHERE email=?");
            $prepq->execute(array($_SESSION['email']));
            $db=null;
            $rowq=$prepq->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        $pwdontmatch="";
        $invalidphone="";
        if(isset($update)){
            $pattern="/^((00)?(973))?[369178][0-9]{7}$/";
            if ($password!=$confirmpassword || !preg_match($pattern, $phone)) {
                if (!preg_match($pattern, $phone)) {
                    $invalidphone="<div style='color:red; text-align:center; font-size: 12px;'>Please enter a valid phone number.</div>";
                }
                if ($password!=$confirmpassword) {
                    $pwdontmatch="<div style='color:red; text-align:center; font-size: 12px;'>Passwords do not match.</div>";
                }
            }
            else {
                try {
                    require($phppath.'callable/connection.php');
                    $prepu=$db->prepare("UPDATE users SET name=?, email=?, password=MD5(?), phone=? WHERE userid=?");
                    $prepu->execute(array($name,$email,$password,$phone,$rowq['userid']));
                    $rowq['name']=$name;
                    $rowq['email']=$email;
                    $rowq['phone']=$phone;
                    $db=null;
                }
                catch (PDOException $e) {
                    echo "Error occured!";
                    die($e->getMessage());
                }
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
                        $newfilename = $rowq['userid'] . '.' . end($temp);
                        move_uploaded_file($_FILES["file"]["tmp_name"], $phppath."upi/" . $newfilename);
                        try {
                            require($phppath.'callable/connection.php');
                            $prepu=$db->prepare("UPDATE users SET profilepicture=? WHERE userid=?");
                            $prepu->execute(array("upi/".$newfilename, $rowq['userid']));
                            $rowq['profilepicture']="upi/".$newfilename;
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
            if ($rowq['profilepicture']!='upi/default.png') {
                try {
                    require($phppath.'callable/connection.php');
                    $prepr=$db->prepare("UPDATE users SET profilepicture=? WHERE userid=?");
                    $prepr->execute(array('upi/default.png',$rowq['userid']));
                    $db=null;
                }
                catch (PDOException $e) {
                    echo "Error occured!";
                    die($e->getMessage());
                }
                $pic=$phppath.$rowq['profilepicture'];
                unlink("$pic");
                $rowq['profilepicture']="upi/default.png";
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
    <h1 class="mt-4 mb-3">Edit Profile</h1>

    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
      </li>
      <li class="breadcrumb-item active">Edit Profile</li>
    </ol>


    <div class="row">
      <div style="margin: auto;" class="col-lg-8 mb-4">
          <div style="text-align:center;">
              <img src="<?php echo $htmlpath.$rowq['profilepicture'];?>" width='150' height='150'></img>
          </div>
          <div style="text-align:center;">
              <form method="POST">
                  <button type="submit" name="resetphoto" class="btn btn-primary">Reset Photo</button>
              </form>
          </div>
        <form method="POST">
          <div class="control-group form-group">
            <div class="controls">
              <label>Name:</label>
              <input value='<?php echo $rowq['name']; ?>' type="text" class="form-control" id="name" name="name" required data-validation-required-message="Please enter your name.">
              
            </div>
          </div>
          <div class="control-group form-group">
            <div class="controls">
              <label>Email Address:</label>
              <input value='<?php echo $rowq['email']; ?>' type="email" class="form-control" id="email" name="email" required data-validation-required-message="Please enter your email address.">
            </div>
          </div>
          <?php echo "$pwdontmatch"; ?>
          <div class="control-group form-group">
            <div class="controls">
              <label>Password:</label>
              <input type="password" class="form-control" id="password" name="password" data-validation-required-message="Please enter your password.">
            </div>
          </div>
          <div class="control-group form-group">
            <div class="controls">
              <label>Confirm Password:</label>
              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" data-validation-required-message="Please confirm your password.">
            </div>
          </div>
          <?php echo "$invalidphone"; ?>
          <div class="control-group form-group">
            <div class="controls">
              <label>Phone Number:</label>
              <input value='<?php echo $rowq['phone']; ?>' type="tel" class="form-control" id="phone" name="phone" required data-validation-required-message="Please enter your phone number.">
            </div>
          </div>

          <div style="text-align:center;">
              <button style="width:75%;" type="submit" class="btn btn-primary" name="update" id="update">Update</button>
          </div>
        </form>
        <br/>
        <div style="text-align:center;" class="container">
            <form method="post" enctype="multipart/form-data">
                Update Profile Picture:
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
