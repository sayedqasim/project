<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

<?php
    require($phppath."callable/admin/authenticateadmin.php");
    extract($_POST);
    if (isset($managerid)) {
        $_SESSION['managerid']=$managerid;
    }
    if (isset($delete)) {
        try {
            require($phppath.'callable/connection.php');
            $prepd=$db->prepare("DELETE FROM users WHERE userid=?");
            $prepd->execute(array($_SESSION['managerid']));
            $db=null;
            header('location:'.$htmlpath.'callable/admin/managerview.php');
            die();
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
    }
    try {
        require($phppath.'callable/connection.php');
        $prepd=$db->prepare("SELECT * FROM users WHERE userid=?");
        $prepd->execute(array($_SESSION['managerid']));
        $db=null;
        $row=$prepd->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
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
<h1 class="mt-4 mb-3">Delete Manager</h1>

<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
  </li>
  <li class="breadcrumb-item active">Delete Manager</li>
</ol>
      <div style='color:red; text-align:center; font-size: 12px;'>Are you sure you want to delete <?php echo $row['name']; ?>?</div>
      <br/>
      <div class='row' >
          <div style='text-align:center;' class='col-md-2' >
              <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['profilepicture']; ?>" alt=''>
          </div>
          <div class='col-md-8' >
              <table >
                  <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                  <tr><td><b>Email:</b></td><td><?php echo $row['email']; ?></td></tr>
                  <tr><td><b>Phone:</b></td><td><?php echo $row['phone']; ?></td></tr>
              </table>
          </div>
          <div style='text-align:center;' class='col-md-2' >
              <form method="POST">
                  <button style="margin-top:5px;width:75%" class='btn btn-primary btn-danger' name='delete' type='submit'>Delete</button>
              </form>
              <br/>
          </div>
      </div>
  </div>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>


</body>

</html>
