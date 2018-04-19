    <!-- Modular Require -->
    <?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php
    extract($_POST);
    $pwdontmatch="";
    $invalidphone="";
    if(isset($register)){
        $pattern="/^((00)?(973))?[369178][0-9]{7}$/";
        if( $password!=$confirmpassword || !preg_match($pattern, $phone) ){
            if($password!=$confirmpassword)
                $pwdontmatch="<div style='color:red; text-align:center; font-size: 12px;'>Passwords do not match.</div>";
            if (!preg_match($pattern, $phone)) {
                $invalidphone="<div style='color:red; text-align:center; font-size: 12px;'>Please enter a valid phone number.</div>";
            }
        }
        else {
            try {
                require($phppath.'callable/connection.php');
                $prep=$db->prepare("INSERT INTO users (name, email, password, phone, profilepicture, usertype) VALUES(?, ?, MD5(?), ?, ?, ?)");
                $prep->execute(array($name, $email, $password, $phone, 'upi/default.png', 'customer'));
                $db=null;
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            $_SESSION['email']=$email;
            $_SESSION['usertype']='customer';
            header('Location:'.$htmlpath.'index.php');
            die();
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
          <h1 class="mt-4 mb-3">Register</h1>

          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
            </li>
            <li class="breadcrumb-item active">Register</li>
          </ol>

          <!-- /.row -->

          <!-- Contact Form -->
          <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
          <div class="row">
            <div style="margin: auto;" class="col-lg-8 mb-4">
              <form method="POST">
                <div class="control-group form-group">
                  <div class="controls">
                    <label>Name:</label>
                    <input value='<?php echo $name; ?>' type="text" class="form-control" id="name" name="name" required data-validation-required-message="Please enter your name.">
                    <p class="help-block"></p>
                  </div>
                </div>
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
                <!-- For success/fail messages -->
                <div style="text-align:center;">
                    <button style="width:75%;" type="submit" class="btn btn-primary" name="register" id="register">Register</button>
                </div>
              </form>
              <div style="text-align:center;" >
                  <a href="login.php" >Or Login!</a>
              </div>
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
