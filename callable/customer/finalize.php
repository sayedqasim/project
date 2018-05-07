<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath.'callable/customer/authenticatecustomer.php'); ?>

<?php
    extract($_POST);
        try {
            require($phppath.'callable/connection.php');
            $db->beginTransaction();
            $prepi=$db->prepare("SELECT * FROM items WHERE itemid=?");
            $prepr=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
            $prepb=$db->prepare("SELECT * FROM branches WHERE restaurantid=?");
            $prepu=$db->prepare("SELECT * FROM users WHERE email=?");
            $prepa=$db->prepare("SELECT * FROM useraddresses WHERE userid=?");
            $transactorder=$db->prepare("INSERT INTO orders (branchid, userid, status, type, payment, stamp) VALUES (?, ?, 'Pending', ?, 'cash', SYSDATE())");
            $transactitems=$db->prepare("INSERT INTO orderitems (orderid, itemid, quantity) VALUES (?, ?, ?)");
            $transactaddress=$db->prepare("INSERT INTO orderaddress (orderid, addressid) VALUES (?, ?)");
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
            $prepu->execute(array($_SESSION['email']));
            $rsu=$prepu->fetch(PDO::FETCH_ASSOC);
            $prepa->execute(array($rsu['userid']));
            $rsa=$prepa->fetchAll(PDO::FETCH_ASSOC);
            $restauranttotal=0;
            if ($itemlist['selectedaddress']=="0:Pickup") {
                $typeinsert="Pickup";
            }
            else {
                $temp=explode(':', $itemlist['selectedaddress']);
                $addressidinsert=$temp[0];
                $typeinsert="Delivery";
            }
            $temp=explode(':', $itemlist['selectedbranch']);
            $branchidinsert=$temp[0];
            $transactorder->execute(array($branchidinsert, $rsu['userid'], $typeinsert));
            $orderidinsert=$db->lastInsertId();
            if ($typeinsert=="Delivery") {
                $transactaddress->execute(array($orderidinsert, $addressidinsert));
            }
            foreach ($itemlist['items'] as $itemidcart => $quantity) {
                $prepi->execute(array($itemidcart));
                $rsi=$prepi->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rsi as $rowi) {
                    $transactitems->execute(array($orderidinsert, $rowi['itemid'], $quantity));
                }
            }
        }
        $db->commit();
        $db=null;
        if (isset($_SESSION['singlerestaurant'])){
            if ($restaurantidcart==$_SESSION['singlerestaurant']){
                unset($_SESSION['cart'][$restaurantidcart]);
            }
        }
        else {
            unset($_SESSION['cart']);
        }

    } catch (PDOException $e) {
        $db->rollback();
        echo "Error occured!";
        die($e->getMessage());
    }
    header("location:".$htmlpath."callable/customer/pastorders.php")
?>
