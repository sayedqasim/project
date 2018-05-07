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
    <h1 class="mt-4 mb-3">Examine Item</h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'callable/customer/browseRestaurants.php';?>">Browse Restaurants</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'callable/customer/displayMenu.php';?>">Menu</a>
        </li>
        <li class="breadcrumb-item active">Examine Item</li>
    </ol>
    <hr/>
<?php
    extract($_POST);
    if (isset($itemid)){
        try {
            require($phppath.'callable/connection.php');
            $prepareitemfeedback=$db->prepare("SELECT * FROM feedbackitems WHERE itemid=?");
            $prepareitemfeedback->execute(array($itemid));
            $feedbackrows=$prepareitemfeedback->fetchAll(PDO::FETCH_ASSOC);
            $prepareiteminfo=$db->prepare("SELECT * FROM items WHERE itemid=?");
            $prepareiteminfo->execute(array($itemid));
            $itemrow=$prepareiteminfo->fetch(PDO::FETCH_ASSOC);
            $prepareitemrating=$db->prepare("SELECT SUM(rating) AS ratingsum, COUNT(rating) AS ratingcount FROM feedbackitems WHERE itemid=?");
            $prepareitemrating->execute(array($itemid));
            $ratingrows=$prepareitemrating->fetch(PDO::FETCH_ASSOC);
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
            <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$itemrow['image']; ?>" alt=''>
        </div>
        <div>
            <table style="margin:auto; text-align:center;">
              <tr><td><b><?php echo $itemrow['title']; ?></b></td></tr>
              <tr><td><?php echo $itemrow['description']; ?></td></tr>
              <tr><td><?php echo $itemrow['price']; ?> BD</td></tr>
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
