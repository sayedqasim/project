<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/customer/authenticatecustomer.php"); ?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<body>

<!-- Navigation -->
<?php require($navigation); ?>

<!-- Page Content -->
<div class="container">
    <h1 class="mt-4 mb-3">Evaluation</h1>

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo $htmlpath.'callable/customer/evaluationview.php';?>">Evaluation</a>
        </li>
        <li class="breadcrumb-item active">Evaluate Order</li>
    </ol>
    <hr/>
<?php
    extract($_POST);
    if (isset($evaluateorder))
        $_SESSION['orderid']=$evaluateorder;
    try {
        require($phppath.'callable/connection.php');
        if (isset($evaluaterestaurant)) {
            $temp=explode(':', $evaluaterestaurant);
            $orderidevaluate=$temp['0'];
            $restaurantidevaluate=$temp['1'];
            $prepareinsertevaluate=$db->prepare("INSERT INTO feedbackrestaurants (orderid, restaurantid, rating, comment, response) VALUES (?, ?, ?, ?, 'Awaiting response..')");
            $prepareinsertevaluate->execute(array($orderidevaluate, $restaurantidevaluate, $rating, $comment));
        }
        if (isset($evaluateitem)) {
            $temp=explode(':', $evaluateitem);
            $orderidevaluate=$temp['0'];
            $itemidevaluate=$temp['1'];
            $prepareinsertevaluate=$db->prepare("INSERT INTO feedbackitems (orderid, itemid, rating, comment, response) VALUES (?, ?, ?, ?, 'Awaiting response..')");
            $prepareinsertevaluate->execute(array($orderidevaluate, $itemidevaluate, $rating, $comment));
        }
        $prepareorders=$db->prepare("SELECT * FROM orders WHERE orderid=?");
        $prepareorders->execute(array($_SESSION['orderid']));
        $orderrows=$prepareorders->fetchAll(PDO::FETCH_ASSOC);
        $prepareitems=$db->prepare("SELECT items.*, orderitems.quantity, orderitems.orderid FROM items, orderitems WHERE (items.itemid=orderitems.itemid) AND (orderitems.orderid=? )");
        $prepareaddress=$db->prepare("SELECT area, address FROM useraddresses, orderaddress WHERE (orderaddress.addressid=useraddresses.addressid) AND (orderaddress.orderid=?)");
        $preparebranch=$db->prepare("SELECT restaurants.*, branches.area, branches.address FROM restaurants, branches, orders WHERE orders.branchid=branches.branchid AND branches.restaurantid=restaurants.restaurantid AND orders.orderid=?");
        $preparefeedbackitems=$db->prepare("SELECT * FROM feedbackitems WHERE orderid=? AND itemid=?");
        $preparefeedbackrestaurants=$db->prepare("SELECT * FROM feedbackrestaurants WHERE orderid=? AND restaurantid=?");
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
            echo "<h5 style='color: blue; text-align:center;'> Order Status: ".$order['status'] ."</b></h5>";
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
                <hr/>
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
            $preparefeedbackitems->execute(array($order['orderid'], $item['itemid']));
            if ($preparefeedbackitems->rowCount()<1) {
                ?>
                <form method="POST">
                <div style="text-align: center;" class="row">
                    <div class="col-md-3"></div>
                        <div class="col-md-3">
                          <label><h6>Rating:</h6></label>
                          <br/>
                          <input style="margin: auto; text-align: center; width:100px;" type="number" min="0" max="5" step="0.1" placeholder="4.0" name="rating" class="form-control" required data-validation-required-message="Please provide ratings."></input>
                        </div>
                        <div class="col-md-3">
                          <label><h6>Comments:</h6></label>
                          <br/>
                          <textarea type="text" placeholder="The food was .." name="comment" required data-validation-required-message="Please provide comments."></textarea>
                        </div>
                    <div class="col-md-3"></div>
                  </div>
                  <br/>
                  <div style="text-align:center;">
                      <button type="submit" class='btn btn-primary' style="width:50%;" name="evaluateitem" value="<?php echo $order['orderid'].':'.$item['itemid'] ?>">Evaluate <b><?php echo $item['title'] ?></b></button>
                  </div>
                </form>
                <?php
            }
            else if ($preparefeedbackitems->rowCount()==1) {
                $feedbackitems=$preparefeedbackitems->fetch(PDO::FETCH_ASSOC);
                ?>
                <br/>
                    <div style="text-align:center;" class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                          <label><h6>Rating:</h6></label>
                          <br/>
                          <input style="text-align: center; width:75px;" value="<?php echo $feedbackitems['rating']."/5.0" ?>" disabled></input>
                        </div>
                        <div class="col-md-3">
                          <label><h6>Comments:</h6></label>
                          <br/>
                          <textarea disabled> <?php echo $feedbackitems['comment'] ?> </textarea>
                        </div>
                        <div class="col-md-3">
                          <label><h6>Response:</h6></label>
                          <br/>
                          <textarea disabled> <?php echo $feedbackitems['response'] ?> </textarea>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                <br/>
                <?php
            }
        }
        if ($order['type']=="Delivery") {
            $deliverycharge=0.7;
            echo "<br/><h6 style='text-align:center;'> Delivery Charge is <b>".number_format($deliverycharge, 3)."BD</b></h6>";
            $restauranttotal+=0.7;
        }
        echo "<hr/>";
        echo "<h6 style='text-align:center;'> Total for " . $branchrow['name'] . " is <b>" . number_format($restauranttotal, 3) . " BD</b></h6>";
        $preparefeedbackrestaurants->execute(array($order['orderid'], $branchrow['restaurantid']));
        if ($preparefeedbackrestaurants->rowCount()<1) {
            ?>
            <form method="POST">
            <div style="text-align: center;" class="row">
                <div class="col-md-3"></div>
                    <div class="col-md-3">
                      <label><h6>Rating:</h6></label>
                      <br/>
                      <input style="margin: auto; text-align: center; width:100px;" type="number" min="0" max="5" step="0.1" placeholder="4.0" name="rating" class="form-control" required data-validation-required-message="Please provide ratings."></input>
                    </div>
                    <div class="col-md-3">
                      <label><h6>Comments:</h6></label>
                      <br/>
                      <textarea type="text" placeholder="The restaurant was .." name="comment" required data-validation-required-message="Please provide comments."></textarea>
                    </div>
                <div class="col-md-3"></div>
              </div>
              <br/>
              <div style="text-align:center;">
                  <button type="submit" class='btn btn-primary' style="width:75%;" name="evaluaterestaurant" value="<?php echo $order['orderid'].':'.$branchrow['restaurantid'] ?>">Evaluate <b><?php echo $branchrow['name'] ?></b></button>
              </div>
            </form>
            <?php
        }
        else if ($preparefeedbackrestaurants->rowCount()==1) {
            $feedbackrestaurant=$preparefeedbackrestaurants->fetch(PDO::FETCH_ASSOC);
            ?>
            <br/>
            <form method="POST">
                <div style="text-align:center;" class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                      <label><h6>Rating:</h6></label>
                      <br/>
                      <input style="text-align: center; width:75px;" value="<?php echo $feedbackrestaurant['rating']."/5.0" ?>" disabled></input>
                    </div>
                    <div class="col-md-3">
                      <label><h6>Comments:</h6></label>
                      <br/>
                      <textarea disabled> <?php echo $feedbackrestaurant['comment'] ?> </textarea>
                    </div>
                    <div class="col-md-3">
                      <label><h6>Response:</h6></label>
                      <br/>
                      <textarea disabled> <?php echo $feedbackrestaurant['response'] ?> </textarea>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </form>
            <br/>
            <?php
        }
        echo "<hr/>";
    }
        $db=null;
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
?>
</div>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
