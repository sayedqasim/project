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

<?php
    extract($_POST);
    if (isset($updaterestaurant)) {
        $index=0;
        foreach ($_SESSION['cart'][$updaterestaurant]['items'] as $itemidupdate => $qtycart) {
            $_SESSION['cart'][$updaterestaurant]['items'][$itemidupdate]=$qty[$index];
            $index++;
        }
    }
    if (isset($removeitem)) {
        $temp=explode(':', $removeitem);
        $restaurantiddelete=(int)$temp[0];
        $itemiddelete=(int)$temp[1];
        unset($_SESSION['cart'][$restaurantiddelete][$itemiddelete]);
    }
    if (isset($removerestaurant))
        unset($_SESSION['cart'][$removerestaurant]);
    if (isset($removecart))
        unset($_SESSION['cart']);
?>

<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Cart</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Cart</li>
    </ol>
    <hr/>

    <?php
    if (!isset($_SESSION['cart']) || $_SESSION['cart']==null) {
        echo "<div style='text-align: center;'>";
        echo "<h3>Cart is empty.</h3>";
        echo "<h4>How about you start filling it up!</h4>";
        echo "<a href='".$htmlpath.'callable/customer/browseRestaurants.php'."'>Browse Restaurants!</a>";
        echo "</div><br/>";
    }
    else {
        try {
            require($phppath.'callable/connection.php');
            $prepi=$db->prepare("SELECT * FROM items WHERE itemid=?");
            $prepr=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
            $carttotal=0;
            foreach ($_SESSION['cart'] as $restaurantidcart => $itemlist) {
                $prepr->execute(array($restaurantidcart));
                $rsr=$prepr->fetchAll(PDO::FETCH_ASSOC);
                $restauranttotal=0;
                foreach ($rsr as $rowr) {
                }
                ?>
                    <div style='text-align:center;'>
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$rowr['logo']; ?>" alt=''>
                    </div>
                    <div>
                        <table style="margin:auto;">
                            <tr><td><b>Name:</b></td><td><?php echo $rowr['name']; ?></td></tr>
                            <tr><td><b>Description:</b></td><td><?php echo $rowr['description']; ?></td></tr>
                        </table>
                    </div>
                    <br/>
                <?php
                foreach ($itemlist['items'] as $itemidcart => $quantity) {
                    $prepi->execute(array($itemidcart));
                    $rsi=$prepi->fetchAll(PDO::FETCH_ASSOC);
                    $itemtotal=0;
                    echo "<form method='POST'>";
                    foreach ($rsi as $rowi) {
                        ?>
                        <div class='row' >
                            <div style='text-align:center;' class='col-md-2' >
                                <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$rowi['image']; ?>" alt=''>
                            </div>
                            <div class='col-md-6' >
                                <table>
                                    <tr><td><b><?php echo $rowi['title']; ?></b></td></tr>
                                    <tr><td>1 Item:</td><td><?php echo $rowi['price']; ?> BD</td></tr>
                                    <tr><td><?php echo $quantity ?> Item(s):</td><td><?php echo number_format($itemtotal=$rowi['price']*$quantity, 3); ?> BD</td></tr>
                                </table>
                            </div>
                            <div style='text-align:center;' class='col-md-4' >
                                <table style="margin:auto;">
                                    <tr>
                                        <td><input style="width: 50px;  margin-right: 5px; margin-top:10px;" type='number' name='qty[]' min="1" value="<?php echo $quantity ?>"></input></td>
                                        <td><button style="margin-top:10px;" class='btn btn-primary btn-danger' type='submit' name='removeitem' value="<?php echo $rowr['restaurantid'].':'.$rowi['itemid'] ?>">Remove</button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php
                        $restauranttotal+=$itemtotal;
                    }
                }
                ?>
                <table style="margin:auto;">
                    <td style="text-align:center;"><button style="margin-top:10px;" class='btn btn-primary' type='submit' name='updaterestaurant' value="<?php echo $rowr['restaurantid'] ?>">Update <?php echo $rowr['name'] ?></button></td>
                    <tr><td><button style="margin-top:10px;" formaction="<?php echo $htmlpath.'callable/customer/checkout.php' ?>" class='btn btn-primary' type='submit' name='checkoutrestaurant' value="<?php echo $rowr['restaurantid'] ?>"><?php echo $rowr['name'].' Checkout (<b>'.number_format($restauranttotal, 3) ?> BD</b>)</button></td></tr>
                </form>
                    <tr><td style="text-align:center;"><button style="margin-top:10px;" class='btn btn-primary btn-danger' type='submit' name='removerestaurant' value="<?php echo $rowr['restaurantid'] ?>">Remove <?php echo $rowr['name'] ?></button></td></tr>
                </table>
                <br/>
                <?php
                $carttotal+=$restauranttotal;
                ?>
                <hr/>
                <?php
            }
            if (count($_SESSION['cart'])>1) {
                ?>
                <form method="POST">
                    <table style="margin:auto;">
                        <tr><td><button style="margin-top:10px;" formaction="<?php echo $htmlpath.'callable/customer/checkout.php' ?>" class='btn btn-primary' type='submit' name='checkoutcart' value="<?php echo $rowr['restaurantid'] ?>">Cart Checkout <?php echo '(<b>'.number_format($carttotal,3) ?> BD</b>)</button></td></tr>
                        <tr><td style="text-align:center;"><button style="margin-top:10px;" class='btn btn-primary btn-danger' type='submit' name='removecart'>Empty Cart</button></td></tr>
                    </table>
                </form>
                <br/>
                <?php
            }
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
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
