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
    if (isset($select)) {
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("INSERT INTO restaurantmanagers (managerid, restaurantid) VALUES(?, ?)");
            $prepq->execute(array($select, $_SESSION['restaurantid']));
            $db=null;
        }
        catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
    }
    if (isset($remove)) {
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("DELETE FROM restaurantmanagers WHERE restaurantid=? AND managerid=?");
            $prepq->execute(array($_SESSION['restaurantid'], $remove));
            $db=null;
        }
        catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
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
<h1 class="mt-4 mb-3">Appoint Restaurant</h1>

<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
  </li>
  <li class="breadcrumb-item">
    <a href="<?php echo $htmlpath.'callable/admin/restaurantview.php';?>">Restaurants</a>
  </li>
  <li class="breadcrumb-item active">Appoint Restaurant</li>
</ol>


<div class="row">
  <div style="margin: auto;" class="col-lg-8 mb-4">
      <div style="text-align:center;">
          <img src="<?php echo $htmlpath.$rowq['logo'];?>" width='150' height='150'></img>
      </div>
      <div>
          <table style="margin:auto;">
              <tr><td><b>Name:</b></td><td><?php echo $rowq['name']; ?></td></tr>
              <tr><td><b>Description:</b></td><td><?php echo $rowq['description']; ?></td></tr>
          </table>
      </div>
      <hr/>
  </div>
</div>

    <?php
    try {
        require($phppath.'callable/connection.php');
        $prepq=$db->prepare("SELECT * FROM users WHERE userid IN (SELECT managerid FROM restaurantmanagers WHERE restaurantid=?)");
        $prepq->execute(array($_SESSION['restaurantid']));
        $db=null;
        $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
    if (count($rowq)<=0) {
        echo "<div style='color:red; text-align:center; font-size: 12px;'>No managers appointed yet.</div>";
    }
    else {
        echo "<div style='color:red; text-align:center; font-size: 12px;'>Is appointed to:</div>";
    foreach ($rowq as $row) {
?>
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
                    <button style="margin-top:5px;" class='btn btn-primary' type='submit' name='remove' value="<?php echo $row['userid'] ?>">Remove</button>
                </form>
            </div>
        </div>
<?php
    }
}
?>
<hr/>
  <div style="text-align:center;">
      <form method="post">
          <input type="text" style="width:64%;" placeholder="Search Managers.." name="searchparameter">
          <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
      </form>
  </div>
  <br/>
  <?php
      $searchparameter="";
      extract($_POST);
          try {
              require($phppath.'callable/connection.php');
              $prepq=$db->prepare("SELECT * FROM users WHERE (name LIKE ? OR email like ? OR phone LIKE ?) AND (usertype='manager') AND (userid NOT IN (SELECT managerid FROM restaurantmanagers WHERE restaurantid=?))");
              $prepq->execute(array("%$searchparameter%","%$searchparameter%","%$searchparameter%",$_SESSION['restaurantid']));
              $db=null;
              $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
              echo "Error occured!";
              die($e->getMessage());
          }
          foreach ($rowq as $row) {
      ?>
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
                          <button style="margin-top:5px;" class='btn btn-primary' type='submit' name='select' value="<?php echo $row['userid'] ?>">Select</button>
                      </form>
                  </div>
              </div>
              <br/>
  <?php
          }
  ?>
</div>
</div>
<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>


</body>

</html>
