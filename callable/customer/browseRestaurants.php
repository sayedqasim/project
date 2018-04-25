<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<link rel="stylesheet" href="<?php echo $htmlpath.'css\link.css';?>" />
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
    <h1 class="mt-4 mb-3">Browse Restaurants</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Browse Restaurants</li>
    </ol>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search menu" name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Search</button></a>
        </form>
    </div>
    <?php
        extract($_POST);
        if (isset($searchparameter)) {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM Restaurants WHERE name LIKE ?");
                $prepq->execute(array("%$searchparameter%"));
                $db=null;
                $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }

            foreach ($rowq as $row) {
                ?>
                <div class='row' >
                    <div style='text-align:center;' class='col-md-2' >  <a class=rowLink href="<?php echo $htmlpath.'callable/customer/displayMenu.php?id='.$row['restaurantid']; ?>">
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''> </a>
                    </div>
                    <div class='col-md-8' >
                      <a class=rowLink href="<?php echo $htmlpath.'callable/customer/displayMenu.php?id='.$row['restaurantid']; ?>">
                        <table>
                          <tr><td><b><?php echo $row['name']; ?></b></td></tr>
                          <tr><td><?php echo $row['description']; ?></td></tr>
                          <tr><td><?php echo "Average Time: ".$row['AvgTime']." min" ?></td></tr>
                          <tr><td><?php echo "Min. Order: BD".$row['MinOrder']; ?></td></tr>
                          <?php if($row['DeliveryType']=="PD") {echo"<tr><td>Delivery Charge: BD".$row['DeliveryCharge']." </td></tr>";}
                            else {echo"<tr><td>Only Pickup </td></tr>";} ?>
                        </table> </a>
                    </div>
                </div> <hr />
                <br/>

    <?php
          }
        }
        else {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM Restaurants");
                $prepq->execute();
                $db=null;
                $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            foreach ($rowq as $row) {
                ?>
                <div class='row' >
                  <div style='text-align:center;' class='col-md-2' > <a href="<?php echo $htmlpath.'callable/customer/displayMenu.php?id='.$row['restaurantid']; ?>">
                      <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''> </a>
                    </div>

                    <div class='col-md-8' >
                      <a class=rowLink href="<?php echo $htmlpath.'callable/customer/displayMenu.php?id='.$row['restaurantid']; ?>">
                        <table>
                          <tr><td><b><?php echo $row['name']; ?></b></td></tr>
                          <tr><td><?php echo $row['description']; ?></td></tr>
                          <tr><td><?php echo "Average Time: ".$row['AvgTime']." min" ?></td></tr>
                          <tr><td><?php echo "Min. Order: BD".$row['MinOrder']; ?></td></tr>
                          <?php if($row['DeliveryType']=="PD") {echo"<tr><td>Delivery Charge: BD".$row['DeliveryCharge']." </td></tr>";}
                            else {echo"<tr><td>Only Pickup </td></tr>";} ?>
                        </table> </a>
                    </div>
                </div> <hr />
                <br/>
    <?php
            }
        }
    ?>
</div>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
