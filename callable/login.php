    <!-- Modular Require -->
    <?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php
    extract($_POST);
    $invalidcredentials="";
    if(isset($login)){
        try {
            require($phppath.'callable/connection.php');
            $prep=$db->prepare("SELECT * FROM users WHERE email=? AND password=MD5(?)");
            $prep->execute(array($email, $password));
            $db=null;
            if ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['email']=$row['email'];
                $_SESSION['usertype']=$row['usertype'];
                header('Location:'.$htmlpath.'index.php');
                die();
            }
            else {
                $invalidcredentials="<div style='color:red; text-align:center; font-size: 12px;'>Invalid Credentials</div>";
            }
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
    }
    else {
        $email="";
        $password="";
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
          <h1 class="mt-4 mb-3">Login</h1>

          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
            </li>
            <li class="breadcrumb-item active">Login</li>
          </ol>

          <!-- /.row -->

          <div class="row">
            <div style="margin: auto;" class="col-lg-8 mb-4">
              <form method="POST">
                <?php echo "$invalidcredentials"; ?>
                <div class="control-group form-group">
                  <div class="controls">
                    <label>Email Address:</label>
                    <input value='<?php echo $email; ?>' type="email" class="form-control" id="email" name="email" required data-validation-required-message="Please enter your email address.">
                  </div>
                </div>
                <div class="control-group form-group">
                  <div class="controls">
                    <label>Password:</label>
                    <input value='<?php echo $password; ?>' type="password" class="form-control" id="password" name="password" required data-validation-required-message="Please enter your password.">
                  </div>
                </div>
                <!-- For success/fail messages -->
                <div style="text-align:center;">
                    <button style="width:75%;" type="submit" class="btn btn-primary" name="login" id="login">Login</button>
                </div>
              </form>
              <div style="text-align:center;" >
                  <a href="register.php" >Or Register!</a>
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
