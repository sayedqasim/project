<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<!-- <link rel="stylesheet" href="<?php //echo $htmlpath.'css\link.css';?>" /> -->
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
    <hr/>
    <div style="text-align:center;">
        <form method="POST">
            <input type="text" style="width:64%;" placeholder="Search .." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
        </form>
    </div>
    <br/>
    <?php
        $searchparameter="";
        extract($_POST);
        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM restaurants WHERE (name LIKE ? OR description LIKE ?) OR (restaurantid IN (SELECT restaurantid FROM items WHERE title LIKE ? OR description LIKE ? OR type LIKE ?)) OR (restaurantid IN (SELECT restaurantid FROM branches WHERE area LIKE ?))");
            $prepq->execute(array_fill(0, 6,"%$searchparameter%"));
            $preparerating=$db->prepare("SELECT SUM(rating) AS ratingsum, COUNT(rating) AS ratingcount FROM feedbackrestaurants WHERE restaurantid=?");
            $db=null;
            $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        foreach ($rowq as $row) {
            $preparerating->execute(array($row['restaurantid']));
            $ratingrow=$preparerating->fetch(PDO::FETCH_ASSOC);
            $averagerating="Not Yet Rated.";
            if ($ratingrow['ratingcount']>0) {
                $averagerating=$ratingrow['ratingsum']/$ratingrow['ratingcount'];
            }
    ?>
            <div class='row'>
                <div style='text-align:center;' class='col-md-2'>
                    <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['logo']; ?>" alt=''>
                </div>
                <div class='col-md-6'>
                    <table >
                        <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                        <tr><td><b>Description:</b></td><td><?php echo $row['description']; ?></td></tr>
                        <tr><td><b>Rating Score:</b></td><td><?php if (is_numeric($averagerating)) echo number_format($averagerating, 1)."/5.0"; else echo $averagerating; ?></td></tr>
                    </table>
                </div>
                <div style='text-align:center;' class='col-md-4' >
                    <form method="POST">
                        <button style="height: 60px;margin-top:20px;" formaction="<?php echo $htmlpath.'callable/customer/displayMenu.php' ?>" class='btn btn-primary' type='submit' name='restaurantid' value="<?php echo $row['restaurantid'] ?>">Menu</button>
                        <button style="height: 60px;margin-top:20px;" formaction="<?php echo $htmlpath.'callable/customer/examinerestaurant.php' ?>" class='btn btn-primary' type='submit' name='restaurantid' value="<?php echo $row['restaurantid'] ?>">Examine</button>
                    </form>
                </div>
            </div>
            <hr/>
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
