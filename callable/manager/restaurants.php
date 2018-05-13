<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

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
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search Restaurants.." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
        </form>
    </div>
    <br/>
    <?php
        $searchparameter="";
        extract($_POST);
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM restaurants WHERE (name LIKE ? OR description like ?) AND (restaurantid IN (SELECT restaurantid FROM restaurantmanagers WHERE managerid=(SELECT userid FROM users WHERE email=?)))");
                $prepq->execute(array("%$searchparameter%","%$searchparameter%", $_SESSION['email']));
                $db=null;
                $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            if (count($rowq)<=0) {
                echo "<div style='color:red; text-align:center; font-size: 12px;'>No restaurants found.</div>";
            }
            else {
            foreach ($rowq as $row) {
        ?>
                <div class='row' >
                    <div style='text-align:center;' class='col-md-2' >
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''>
                    </div>
                    <div class='col-md-5' >
                        <table >
                            <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                            <tr><td><b>Description:</b></td><td><?php echo $row['description']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-5' >
                        <form>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/manager/branchview.php' ?>" class='btn btn-primary' type='submit' name='restaurantid' value="<?php echo $row['restaurantid'] ?>">Branches</button>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/manager/itemview.php' ?>" class='btn btn-primary' type='submit' name='restaurantid' value="<?php echo $row['restaurantid'] ?>">Items</button>
                        </form>
                    </div>
                </div>
                <hr/>
    <?php
            }
        }
    ?>
</div>
<br/>
<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
