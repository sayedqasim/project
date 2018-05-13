<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/branch/authenticatebranch.php"); ?>

<!DOCTYPE html>
<html lang="en">

<?php
$page = $_SERVER['PHP_SELF'];
$sec = "10";
?>

<!-- Head -->
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">

  <title>Food Ordering</title>

  <!-- Bootstrap core CSS -->
  <link href='<?php echo $htmlpath.'vendor/bootstrap/css/bootstrap.min.css' ?>' rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href='<?php echo $htmlpath.'css/modern-business.css' ?>' rel="stylesheet">

  <script>
  var seconds=9;
  var x = setInterval(function() {
      document.getElementById("countdown").innerHTML ="Refreshing In: " + seconds + " Seconds ";
      seconds--;
  }, 1000);
  </script>
</head>

<body>

<!-- Navigation -->
<?php require($navigation); ?>

<!-- Page Content -->
<div class="container">
    <h1 class="mt-4 mb-3">Panel</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Panel</li>
    </ol>
    <hr/>
    <h6><div style="text-align:center; color:red;" id="countdown">Refreshing In: 10 Seconds</div></h6>
    <hr/>
<?php
    extract($_POST);
    try {
        require($phppath.'callable/connection.php');
        $preparechangestatus=$db->prepare("UPDATE orders SET status=? WHERE orderid=?");
        if (isset($updatestatus))
            $preparechangestatus->execute(array($stringstatus, $updatestatus));

        $temp=explode('-',$_SESSION['email']);
        $restaurantname=$temp[0];
        $temp=explode('@',$temp[1]);
        $brancharea=$temp[0];
        $prepareretrievebranchid=$db->prepare("SELECT branchid FROM branches WHERE (restaurantid=(SELECT restaurantid FROM restaurants WHERE REPLACE(name, ' ', '')=?)) AND (REPLACE(area, ' ', '')=?)");
        $prepareretrievebranchid->execute(array($restaurantname, $brancharea));
        $retrievedbranchid=$prepareretrievebranchid->fetch(PDO::FETCH_ASSOC);
        $prepareacknowledge=$db->prepare("UPDATE orders SET status='Acknowledged' WHERE branchid=? AND status='Pending'");
        $prepareacknowledge->execute(array($retrievedbranchid['branchid']));
        $prepareorders=$db->prepare("SELECT * FROM orders WHERE branchid=? AND status<>'Fulfilled' ORDER BY stamp DESC");
        $prepareorders->execute(array($retrievedbranchid['branchid']));
        $orderrows=$prepareorders->fetchAll(PDO::FETCH_ASSOC);
        $prepareitems=$db->prepare("SELECT items.*, orderitems.quantity, orderitems.orderid FROM items, orderitems WHERE (items.itemid=orderitems.itemid) AND (orderitems.orderid=? )");
        $prepareaddress=$db->prepare("SELECT area, address FROM useraddresses, orderaddress WHERE (orderaddress.addressid=useraddresses.addressid) AND (orderaddress.orderid=?)");
        $preparebranch=$db->prepare("SELECT restaurants.*, branches.area, branches.address FROM restaurants, branches, orders WHERE orders.branchid=branches.branchid AND branches.restaurantid=restaurants.restaurantid AND orders.orderid=?");

        if(count($orderrows)==0)
            echo "<div style='background-color: lightgray;'><h5 style='color: blue; text-align:center;'>No Pending Orders</b></h5></div>";

        foreach ($orderrows as $order) {
            $restauranttotal=0;
            $prepareitems->execute(array($order['orderid']));
            $itemrows=$prepareitems->fetchAll(PDO::FETCH_ASSOC);

            $preparebranch->execute(array($order['orderid']));
            $branchrow=$preparebranch->fetch(PDO::FETCH_ASSOC);

            $delivery="Pickup";
            if ($order['type']=="Delivery") {
                $prepareaddress->execute(array($order['orderid']));
                $addressrow=$prepareaddress->fetch(PDO::FETCH_ASSOC);
                $delivery=$addressrow['area'];
            }
            echo "<div style='background-color: lightgray;'><h5 style='color: blue; text-align:center;'> Order Status: ".$order['status'] ."</b></h5></div>";
            ?>
            <div style='text-align:center;'>
                <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$branchrow['logo']; ?>" alt=''>
            </div>
            <div>
                <table style="margin:auto;">
                    <tr><td><b>Name: </b></td><td><?php echo $branchrow['name']; ?></td></tr>
                    <tr><td><b>Description: </b></td><td><?php echo $branchrow['description']; ?></td></tr>
                    <tr><td><b>Selected Branch: </b></td><td><?php echo $branchrow['area']; ?>
                    <tr><td><b>Delivered To: </b></td><td><?php echo $delivery; ?>
                    <tr><td><b>Date & Time: </b></td><td><?php echo date("H:i:s - d-M-Y", strtotime($order['stamp'])); ?>
                </table>
            </div>
            <?php
            foreach ($itemrows as $item) {
            ?>
                <div class='row' >
                    <div class='col-md-3'></div>
                    <div style="margin:auto;">
                        <img class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$item['image']; ?>" alt=''>
                    </div>
                    <div style="margin:auto;">
                        <table style="margin-top: 10px; margin-bottom: 10px;" border="1">
                            <tr><td style="text-align: center;" colspan="3"><b><?php echo $item['title']; ?></b></td></tr>
                            <tr>
                                <td>Qty</td>
                                <td>Price</td>
                                <td>Total</td>
                            </tr>
                            <tr>
                                <td><?php echo $item['quantity'] ?></td>
                                <td><?php echo $item['price']." BD"; ?></td>
                                <td><b><?php echo number_format($itemtotal=$item['price']*$item['quantity'], 3); ?> BD<b></td>
                            </tr>
                        </table>
                    </div>
                    <div class='col-md-3'></div>
                </div>
            <?php
            $restauranttotal+=$itemtotal;
        }
        if ($order['type']=="Delivery") {
            $deliverycharge=0.7;
            echo "<br/><h6 style='text-align:center;'> Delivery Charge is <b>".number_format($deliverycharge, 3)."BD</b></h6>";
            $restauranttotal+=0.7;
        }
        echo "<h6 style='text-align:center;'> Total for " . $branchrow['name'] . " is <b>" . number_format($restauranttotal, 3) . " BD</b></h6>";
        if ($order['status']=='Acknowledged') {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='stringstatus' value='In-Process'></input>";
            echo "<div style='text-align:center;'><button style='width: 60%;' type='submit' name='updatestatus' value='".$order['orderid']."' class='btn btn-primary'>Mark As <b>In-Process</b></button></div>";
            echo "</form>";
        }
        else if ($order['status']=='In-Process' && $order['type']=='Delivery') {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='stringstatus' value='Out-For-Delivery'></input>";
            echo "<div style='text-align:center;'><button style='width: 60%;' type='submit' name='updatestatus' value='".$order['orderid']."' class='btn btn-primary'>Mark As <b>Out-For-Delivery</b></button></div>";
            echo "</form>";
        }
        else if ($order['status']=='In-Process' && $order['type']=='Pickup') {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='stringstatus' value='Ready-For-Pickup'></input>";
            echo "<div style='text-align:center;'><button style='width: 60%;' type='submit' name='updatestatus' value='".$order['orderid']."' class='btn btn-primary'>Mark As <b>Ready-For-Pickup</b></button></div>";
            echo "</form>";
        }
        else if ($order['status']=='Ready-For-Pickup' || $order['status']=='Out-For-Delivery') {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='stringstatus' value='Fulfilled'></input>";
            echo "<div style='text-align:center;'><button style='width: 60%;' type='submit' name='updatestatus' value='".$order['orderid']."' class='btn btn-primary'>Mark As <b>Fulfilled</b></button></div>";
            echo "</form>";
        }
        echo "<hr/>";
    }
        $db=null;
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }

?>
<hr/>
</div>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
