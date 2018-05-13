<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/customer/authenticatecustomer.php"); ?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<script>
    function GetXmlHttpObject(){
        var retxmlHttp=null;
        try {
            retxmlHttp=new XMLHttpRequest();
        } catch (e) {
            try {
                retxmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                retxmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
        return retxmlHttp;
    }
    function showOrder(orderid) {
        var xmlHttp=GetXmlHttpObject();
        if (xmlHttp==null) {
            alert ("Your browser does not support AJAX!");
            return;
        }
        var url="getorder.php?selectedorder="+orderid;
        xmlHttp.onreadystatechange=function() {
            if (xmlHttp.readyState==4) {
                document.getElementById("selectedidmodal").innerHTML=xmlHttp.responseText;
            }
        }
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
    }

</script>


<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    padding-bottom: 40px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */

</style>

<body>



<!-- Navigation -->
<?php require($navigation); ?>

<!-- Page Content -->
<div class="container">
    <h1 class="mt-4 mb-3">Past Orders</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Past Orders</li>
    </ol>
    <hr/>
<?php
    extract($_POST);
    try {
        require($phppath.'callable/connection.php');
        if (isset($deleteorder)) {
            $prepcheck=$db->prepare("SELECT * FROM orders WHERE orderid=? AND status='Pending'");
            $prepcheck->execute(array($deleteorder));
            if ($prepcheck->rowCount()==1) {
                $prepdeleteaddress=$db->prepare("DELETE FROM orderaddress WHERE orderid=?");
                $prepdeleteaddress->execute(array($deleteorder));
                $prepdeleteitems=$db->prepare("DELETE FROM orderitems WHERE orderid=?");
                $prepdeleteitems->execute(array($deleteorder));
                $prepdeleteorder=$db->prepare("DELETE FROM orders WHERE orderid=?");
                $prepdeleteorder->execute(array($deleteorder));
                echo "<div style='color:red; text-align:center; font-size: 12px;'>$deleteorder Deleted Successfully.</div>";
            }
            else {
                echo "<div style='color:red; text-align:center; font-size: 12px;'>Could not delete $deleteorder: Rule Broken.</div>";
            }
            echo "<br/>";
        }
        if (isset($evaluaterestaurant)) {
            $temp=explode(':', $evaluaterestaurant);
            $orderidevaluate=$temp['0'];
            $restaurantidevaluate=$temp['1'];
            $prepareinsertevaluate=$db->prepare("INSERT INTO feedbackrestaurants (orderid, restaurantid, rating, comment, response) VALUES (?, ?, ?, ?, 'Awaiting response..')");
            $prepareinsertevaluate->execute(array($orderidevaluate, $restaurantidevaluate, $rating, $comment));
        }
        if (isset($evaluateitem)) {
            $temp=explode(':', $evaluaterestaurant);
            $orderidevaluate=$temp['0'];
            $restaurantidevaluate=$temp['1'];
            $prepareinsertevaluate=$db->prepare("INSERT INTO feedbackrestaurants (orderid, restaurantid, rating, comment, response) VALUES (?, ?, ?, ?, 'Awaiting response..')");
            $prepareinsertevaluate->execute(array($orderidevaluate, $restaurantidevaluate, $rating, $comment));
        }
        $prepareorders=$db->prepare("SELECT * FROM orders WHERE userid=(SELECT userid FROM users WHERE email=?) ORDER BY stamp DESC");
        $prepareorders->execute(array($_SESSION['email']));
        $orderrows=$prepareorders->fetchAll(PDO::FETCH_ASSOC);
        $prepareitems=$db->prepare("SELECT items.*, orderitems.quantity, orderitems.orderid FROM items, orderitems WHERE (items.itemid=orderitems.itemid) AND (orderitems.orderid=? )");
        $prepareaddress=$db->prepare("SELECT area, address FROM useraddresses, orderaddress WHERE (orderaddress.addressid=useraddresses.addressid) AND (orderaddress.orderid=?)");
        $preparebranch=$db->prepare("SELECT restaurants.*, branches.area, branches.address FROM restaurants, branches, orders WHERE orders.branchid=branches.branchid AND branches.restaurantid=restaurants.restaurantid AND orders.orderid=?");
        $preparemodal=$db->prepare("SELECT orderid FROM orders WHERE userid=(SELECT userid FROM users WHERE email=?) ORDER BY stamp DESC");
        $preparemodal->execute(array($_SESSION['email']));
        $modalidrows=$preparemodal->fetchAll(PDO::FETCH_ASSOC);
        if (count($orderrows)==0) {
            echo "<div style='text-align:center; font-size: 16px;'>No past orders found, <a href='".$htmlpath.'callable/customer/browseRestaurants.php'."'>Browse</a> Restaurants Now!</div>";
        }
        else {
            ?>
            <!-- Trigger/Open The Modal -->
        <div style="text-align:center;">
            <button style="width: 50%; text-align:center;"id="myBtn" class="btn btn-primary">Re-Order</button>
        </div>
        <?php
        }
        ?>
        <hr/>
        <!-- The Modal -->
        <div id="myModal" class="modal">
          <!-- Modal content -->
          <div class="modal-content">
            <span class="close">&times;</span>
            <br/>
            <form method="POST">
                <label>Select Order ID:</label>
                <select onChange="showOrder(this.value)">
                <option value=""></option>
                <?php foreach($modalidrows as $modalorderid){
                    echo "<option value='".$modalorderid['orderid']."'>".$modalorderid['orderid']."</option>";
                }
                ?>
            </select>
            </form>
            <div id="selectedidmodal">
            </div>
          </div>
        </div>

        <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        </script>


        <?php
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
            echo "<div style='background-color: lightgray;'><h5 style='color: blue; text-align:center;'> Order Id: ".$order['orderid']."<br/>Order Status: ".$order['status'] ."</b></h5></div>";
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
        if ($order['status']=='Pending') {
            ?>
            <form style="text-align:center;" method="POST">
                <button style="width:40%;" class="btn btn-primary btn-danger" type="submit" name="deleteorder" value="<?php echo $order['orderid'] ?>">Delete</button>
            </form>
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
