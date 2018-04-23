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
    <h1 class="mt-4 mb-3">Browse Menu</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'callable/customer/browseRestaurants.php';?>">Browse Restaurants</a>
    </li>
        <li class="breadcrumb-item active">Menu</li>
    </ol>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search items" name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Search</button></a>
        </form>
    </div>

    <?php
        extract($_POST);
        //Add to cart
        if(isset($restaurantidx)){
          if(!isset($_SESSION['cart'])){ //create cart
          $items[]=array('rid'=>$restaurantidx,'iid' => $itemidx,'qty' => $qty);
          $_SESSION['cart']=$items; }
          else { //add to cart
            $item=array('rid'=>$restaurantidx,'iid' => $itemidx,'qty' => $qty);
            $_SESSION['cart'][]=$item;
          }
        }
        if (isset($searchparameter)) {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM items WHERE restaurantid LIKE ? AND title LIKE ? ");
                $x="0";
                if(isset($_GET['id'])){
                  $x=$_GET['id']; }
                $prepq->execute(array("%$x%","%$searchparameter%"));
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
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['image']; ?>" alt=''>
                    </div>
                    <div class='col-md-8' >

                        <table>
                          <tr><td><b><?php echo $row['title']; ?></b></td></tr>
                          <tr><td><?php echo $row['description']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-2' >
                      <form>
                          <input type='hidden' name='restaurantidx' value="<?php echo $row['restaurantid'] ?>">
                          <textarea style="border: 2px solid red; border-radius: 4px; width: 25%; height: 5%; resize: none; text-align: center;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/customer/displayMenu.php' ?>" type='submit' name='qty' value="<?php echo $row['userid'] ?>">1</textarea>
                          <button style="margin-bottom:30px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/customer/displayMenu.php?id='.$row['restaurantid']; ?>" class='btn btn-primary' type='submit' name='itemidx' value="<?php echo $row['itemid'] ?>">Add</button>
                      </form>
                  </div>
                </div> <hr />
                <br/>
    <?php
          }
        }
        else {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM items WHERE restaurantid LIKE ? ");
                $x="0";
                if(isset($_GET['id'])){
                  $x=$_GET['id']; }
                $prepq->execute(array("%$x%"));
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
                      <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['image']; ?>" alt=''>
                    </div>
                    <div class='col-md-8' >
                        <table >
                          <tr><td><b><?php echo $row['title']; ?></b></td></tr>
                          <tr><td><?php echo $row['description']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-2' >
                        <form>
                            <input type='hidden' name='restaurantidx' value="<?php echo $row['restaurantid'] ?>">
                            <textarea style="border: 2px solid red; border-radius: 4px; width: 25%; height: 5%; resize: none; text-align: center;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/customer/displayMenu.php' ?>" type='submit' name='qty' value="<?php echo $row['userid'] ?>">1</textarea>
                            <button style="margin-bottom:30px;"  formmethod="POST" formaction="<?php echo $htmlpath.'callable/customer/displayMenu.php?id='.$row['restaurantid']; ?>" class='btn btn-primary' type='submit' name='itemidx' value="<?php echo $row['itemid'] ?>">Add</button>
                        </form>
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
