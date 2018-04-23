<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

<?php
    require($phppath."callable/admin/authenticateadmin.php");
    extract($_POST);
    $addedsuccessfully="";
    $emailexists="";
    $pwdontmatch="";
    $invalidphone="";
    if(isset($addmanager)){
        $pattern="/^((00)?(973))?[369178][0-9]{7}$/";
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM users WHERE email=?");
            $prepq->execute(array($email));
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        if( $password!=$confirmpassword || !preg_match($pattern, $phone) || $prepq->rowCount()==1 ){
            if($password!=$confirmpassword)
                $pwdontmatch="<div style='color:red; text-align:center; font-size: 12px;'>Passwords do not match.</div>";
            if (!preg_match($pattern, $phone))
                $invalidphone="<div style='color:red; text-align:center; font-size: 12px;'>Please enter a valid phone number.</div>";
            if ($prepq->rowCount()==1)
                $emailexists="<div style='color:red; text-align:center; font-size: 12px;'>Email is already associated with an account.</div>";
        }
        else {
            try {
                require($phppath.'callable/connection.php');
                $prep=$db->prepare("INSERT INTO users (name, email, password, phone, profilepicture, usertype) VALUES(?, ?, MD5(?), ?, ?, ?)");
                $prep->execute(array($name, $email, $password, $phone, 'upi/default.png', 'manager'));
                $db=null;
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            $addedsuccessfully="<div style='color:red; text-align:center; font-size: 12px;'>$name has been added succesfully.</div>";
            $name="";
            $email="";
            $password="";
            $confirmpassword="";
            $phone="";
        }
    }
    else {
        $name="";
        $email="";
        $password="";
        $confirmpassword="";
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
  <h1 class="mt-4 mb-3">Add Manager</h1>

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'callable/admin/managerview.php';?>">Managers</a>
    </li>
    <li class="breadcrumb-item active">Add Manager</li>
  </ol>

  <!-- /.row -->

  <div class="row">
    <div style="margin: auto;" class="col-lg-8 mb-4">
      <?php echo "$addedsuccessfully"; ?>
      <form method="POST">
        <div class="control-group form-group">
          <div class="controls">
            <label>Name:</label>
            <input value='<?php echo $name; ?>' type="text" class="form-control" id="name" name="name" required data-validation-required-message="Please enter your name.">

          </div>
        </div>
        <?php echo "$emailexists"; ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Email Address:</label>
            <input value='<?php echo $email; ?>' type="email" class="form-control" id="email" name="email" required data-validation-required-message="Please enter your email address.">
          </div>
        </div>
        <?php echo "$pwdontmatch"; ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Password:</label>
            <input value='<?php echo $password; ?>' type="password" class="form-control" id="password" name="password" required data-validation-required-message="Please enter your password.">
          </div>
        </div>
        <div class="control-group form-group">
          <div class="controls">
            <label>Confirm Password:</label>
            <input value='<?php echo $confirmpassword; ?>' type="password" class="form-control" id="confirmpassword" name="confirmpassword" required data-validation-required-message="Please confirm your password.">
          </div>
        </div>
        <?php echo "$invalidphone"; ?>
        <div class="control-group form-group">
          <div class="controls">
            <label>Phone Number:</label>
            <input value='<?php echo $phone; ?>' type="tel" class="form-control" id="phone" name="phone" required data-validation-required-message="Please enter your phone number.">
          </div>
        </div>

        <div style="text-align:center;">
            <button style="width:75%;" type="submit" class="btn btn-primary" name="addmanager" id="addmanager">Add Manager</button>
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
