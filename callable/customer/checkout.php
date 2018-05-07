<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath.'callable/customer/authenticatecustomer.php'); ?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<body>

<!-- Navigation -->
<?php require($navigation); ?>

<?php
    extract($_POST);
?>

<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Checkout</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'callable/customer/viewCart.php';?>">Cart</a>
    </li>
        <li class="breadcrumb-item active">Checkout</li>
    </ol>
    <hr/>

    <?php
        $message="";
        $disabled="";
        if (isset($checkoutcart))
            unset($_SESSION['singlerestaurant']);
        if (isset($checkoutrestaurant)) {
            $_SESSION['singlerestaurant']=$checkoutrestaurant;
        }
        try {
            require($phppath.'callable/connection.php');
            if (isset($selectedbranch)) {
                $temp=explode(':',$selectedbranch);
                $setrestidbranch=$temp[0];
                $setbranchid=$temp[1];
                $prepbs=$db->prepare("SELECT area FROM branches WHERE branchid=?");
                $prepbs->execute(array($setbranchid));
                $rowbrancharea=$prepbs->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['cart'][$setrestidbranch]['selectedbranch']=$setbranchid.":".$rowbrancharea[0]['area'];
            }
            if (isset($selectedaddress)) {
                $temp=explode(':', $selectedaddress);
                $setrestidaddress=$temp[0];
                $setaddressid=$temp[1];
                $prepas=$db->prepare("SELECT area FROM useraddresses WHERE addressid=?");
                $prepas->execute(array($setaddressid));
                $rowaddressarea=$prepas->fetchAll(PDO::FETCH_ASSOC);
                if ($setaddressid==0)
                    $_SESSION['cart'][$setrestidaddress]['selectedaddress']="0:Pickup";
                else
                    $_SESSION['cart'][$setrestidaddress]['selectedaddress']=$setaddressid.":".$rowaddressarea[0]['area'];

            }
            $prepi=$db->prepare("SELECT * FROM items WHERE itemid=?");
            $prepr=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
            $prepb=$db->prepare("SELECT * FROM branches WHERE restaurantid=?");
            $prepu=$db->prepare("SELECT * FROM users WHERE email=?");
            $prepa=$db->prepare("SELECT * FROM useraddresses WHERE userid=?");
            $carttotal=0;
            foreach ($_SESSION['cart'] as $restaurantidcart => $itemlist) {
                if (isset($_SESSION['singlerestaurant']))
                    if ($restaurantidcart!=$_SESSION['singlerestaurant'])
                        continue;
                $prepr->execute(array($restaurantidcart));
                $rsr=$prepr->fetchAll(PDO::FETCH_ASSOC);
                $prepb->execute(array($restaurantidcart));
                $rsb=$prepb->fetchAll(PDO::FETCH_ASSOC);
                if (count($rsb)<1) {
                    echo "<div style='color:red; text-align:center; font-size: 12px;'>No branches found for ".$rsr[0]['name'].", contact us to resolve this issue.</div>";
                    echo "<hr/>";
                    continue;
                }
                if (!isset($_SESSION['email'])) {

                }
                else {
                    $prepu->execute(array($_SESSION['email']));
                    $rsu=$prepu->fetch(PDO::FETCH_ASSOC);
                    $prepa->execute(array($rsu['userid']));
                    $rsa=$prepa->fetchAll(PDO::FETCH_ASSOC);
                }
                $restauranttotal=0;
                foreach ($rsr as $rowr) {
                }
                ?>
                    <div style='text-align:center;'>
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$rowr['logo']; ?>" alt=''>
                    </div>
                    <div>
                        <table style="margin:auto;">
                            <tr><td><b>Name: </b></td><td><?php echo $rowr['name']; ?></td></tr>
                            <tr><td><b>Description: </b></td><td><?php echo $rowr['description']; ?></td></tr>
                            <tr><td><b>Selected Branch: </b></td><td><?php echo substr(strrchr($itemlist['selectedbranch'],':'),1); ?>
                            <tr><td><b>Delivered To: </b></td><td><?php echo substr(strrchr($itemlist['selectedaddress'],':'),1); ?>
                        </table>
                    </div>
                    <br/>
                <?php
                if ($itemlist['selectedbranch']=="0:Not Selected") {
                    $message="<div style='color:red; text-align:center; font-size: 12px;'>Please select an appropriate branch for all selected restaurants.</div>";
                    $disabled="disabled";
                }
                foreach ($itemlist['items'] as $itemidcart => $quantity) {
                    $prepi->execute(array($itemidcart));
                    $rsi=$prepi->fetchAll(PDO::FETCH_ASSOC);
                    $itemtotal=0;
                    echo "<form method='POST'>";
                    foreach ($rsi as $rowi) {
                        ?>
                        <div class='row' >
                            <div class='col-md-3'></div>
                            <div style="margin:auto;">
                                <img class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$rowi['image']; ?>" alt=''>
                            </div>
                            <div style="margin:auto;">
                                <table style="margin-top: 10px; margin-bottom: 10px;" border="1">
                                    <tr><td style="text-align: center;" colspan="3"><b><?php echo $rowi['title']; ?></b></td></tr>
                                    <tr>
                                        <td>Qty</td>
                                        <td>Price</td>
                                        <td>Total</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $quantity ?></td>
                                        <td><?php echo $rowi['price']." BD"; ?></td>
                                        <td><b><?php echo number_format($itemtotal=$rowi['price']*$quantity, 3); ?> BD<b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class='col-md-3'></div>
                        </div>
                        <?php
                        $restauranttotal+=$itemtotal;
                    }
                }
                if ($itemlist['selectedaddress']!="0:Pickup") {
                    $deliverycharge=0.7;
                    echo "<br/><h6 style='text-align:center;'> Delivery Charge is <b>".number_format($deliverycharge, 3)." BD</b></h6>";
                    $restauranttotal+=0.7;
                }
                echo "<h6 style='text-align:center;'> Total for " . $rowr['name'] . " is <b>" . number_format($restauranttotal, 3) . " BD</b></h6>";
                ?>
                </form>
                <br/>
                <?php
                echo "<h6>Please Select Preferred Branch:</h6>";
                echo "<div style='text-align: center;'><form method='POST'>";
                echo "<select name='selectedbranch' style='width: 30%;'>";
                foreach ($rsb as $branch) {
                    echo "<option value='".$rowr['restaurantid'].':'.$branch['branchid']."'>".$branch['area'].':'.$branch['address']."</option>";
                }
                echo "</select><br/><button style='margin-top:10px;' class='btn btn-primary' type='submit'>Select Branch</button>";
                echo "</form></div>";

                echo "<br/>";

                echo "<h6>Please Select Preferred Address:</h6>";
                echo "<div style='text-align: center;'><form method='POST'>";
                echo "<select name='selectedaddress' style='width: 30%;'>";
                echo "<option value='".$rowr['restaurantid'].":0'>Pickup</option>";
                foreach ($rsa as $address) {
                    echo "<option value='".$rowr['restaurantid'].':'.$address['addressid']."'>".$address['area'].':'.$address['address']."</option>";
                }
                echo "</select><br/><button style='margin-top:10px;' class='btn btn-primary' type='submit'>Select Address</button>";
                echo "</form></div>";

                $carttotal+=$restauranttotal;
                ?>
                <hr/>
                <?php
            }
            echo $message;
            $db=null;
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }
        if ($message=="" && $disabled=="") {
            ?>
            <form method="POST" action="<?php echo $htmlpath.'callable/customer/transact.php' ?>">
                <table style="margin:auto;">
                    <tr><td><button style="margin-top:10px;" class='btn btn-primary' type='submit' name='proceed' <?php echo $disabled ?>>Proceed With Order <?php echo '(<b>'.number_format($carttotal, 3) ?> BD</b>)</button></td></tr>
                </table>
            </form>
            <?php
        }
    ?>
    <br/>
</div>
<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
