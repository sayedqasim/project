<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<body>

<!-- Navigation -->
<?php require($navigation); ?>

<!-- Page Content -->
<div class="container">
    <h1 class="mt-4 mb-3">Examine Restaurant</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'callable/customer/browseRestaurants.php';?>">Browse Restaurants</a>
        </li>
        <li class="breadcrumb-item active">Examine Restaurant</li>
    </ol>
    <hr/>
<?php
    extract($_POST);
    if (isset($restaurantid)){
        try {
            require($phppath.'callable/connection.php');
            $preparerestaurantfeedback=$db->prepare("SELECT * FROM feedbackrestaurants WHERE restaurantid=?");
            $preparerestaurantfeedback->execute(array($restaurantid));
            $feedbackrows=$preparerestaurantfeedback->fetchAll(PDO::FETCH_ASSOC);
            $preparerestaurantinfo=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
            $preparerestaurantinfo->execute(array($restaurantid));
            $restaurantrow=$preparerestaurantinfo->fetch(PDO::FETCH_ASSOC);
            $preparerestaurantrating=$db->prepare("SELECT SUM(rating) AS ratingsum, COUNT(rating) AS ratingcount FROM feedbackrestaurants WHERE restaurantid=?");
            $preparerestaurantrating->execute(array($restaurantid));
            $ratingrows=$preparerestaurantrating->fetch(PDO::FETCH_ASSOC);
            $averagerating="Not Yet Rated.";
            if ($ratingrows['ratingcount']>0)
                $averagerating=$ratingrows['ratingsum']/$ratingrows['ratingcount'];
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        ?>
        <div style='text-align:center;'>
            <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$restaurantrow['logo']; ?>" alt=''>
        </div>
        <div>
            <table style="margin:auto; text-align:center;">
              <tr><td><b><?php echo $restaurantrow['name']; ?></b></td></tr>
              <tr><td><?php echo $restaurantrow['description']; ?></td></tr>
              <tr><td><?php if (is_numeric($averagerating)) echo number_format($averagerating, 1)."/5.0"; else echo $averagerating; ?></td></tr>
            </table>
        </div>
        <hr/>
        <?php
        foreach ($feedbackrows as $feedback) {
            ?>
            <div style="text-align:center;" class="row">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                  <label><h6>Rating:</h6></label>
                  <br/>
                  <input style="text-align: center; width:75px;" value="<?php echo $feedback['rating']."/5.0" ?>" disabled></input>
                </div>
                <div class="col-md-3">
                  <label><h6>Comments:</h6></label>
                  <br/>
                  <textarea disabled> <?php echo $feedback['comment'] ?> </textarea>
                </div>
                <div class="col-md-3">
                  <label><h6>Response:</h6></label>
                  <br/>
                  <textarea disabled> <?php echo $feedback['response'] ?> </textarea>
                </div>
                <div class="col-md-2"></div>
            </div>
            <hr/>
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
