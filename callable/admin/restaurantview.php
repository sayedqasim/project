<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/admin/authenticateadmin.php"); ?>

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
    <h1 class="mt-4 mb-3">Restaurants</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Restaurants</li>
    </ol>
    <div style="text-align:center;">
        <a href="<?php echo $htmlpath.'callable/admin/addRestaurant.php' ?>" ><button style="width:75%;" class="btn btn-primary">Add Restaurant</button></a>
    </div>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search Restaurants.." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
        </form>
    </div>
    <?php
        extract($_POST);
        if (isset($searchparameter)) {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM restaurants WHERE name LIKE ? OR description like ?");
                $prepq->execute(array("%$searchparameter%","%$searchparameter%"));
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
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''>
                    </div>
                    <div class='col-md-8' >
                        <table >
                            <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                            <tr><td><b>Description:</b></td><td><?php echo $row['description']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-2' >
                        <form>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/editRestaurant.php' ?>" class='btn btn-primary' type='submit' name='restaurantid' value="<?php echo $row['restaurantid'] ?>">Edit</button>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/deleteRestaurant.php' ?>" class='btn btn-primary' type='submit' name='restaurantid' value="<?php echo $row['restaurantid'] ?>">Delete</button>
                        </form>
                    </div>
                </div>
                <br/>
    <?php
            }
          }
    ?>
    <hr/>
    <?php
        if (isset($viewall)) {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM restaurants");
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
                    <div style='text-align:center;' class='col-md-2' >
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''>
                    </div>
                    <div class='col-md-8' >
                        <table>
                            <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                            <tr><td><b>Description:</b></td><td><?php echo $row['description']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-2' >
                        <form>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/editmanager.php' ?>" class='btn btn-primary' type='submit' name='managerid' value="<?php echo $row['userid'] ?>">Edit</button>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/deletemanager.php' ?>" class='btn btn-primary' type='submit' name='managerid' value="<?php echo $row['userid'] ?>">Delete</button>
                        </form>
                    </div>
                </div>
                <br/>
    <?php
            }
        } else {
            ?>
            <div style="text-align:center;">
                <form method="post">
                    <button type="submit" style="width:75%;" class="btn btn-primary" name="viewall">View All Restaurants</button>
                </form>
            </div>
            <br/>
    <?php
        }
    ?>
</div>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
