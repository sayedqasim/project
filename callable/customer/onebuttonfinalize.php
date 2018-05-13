<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath.'callable/customer/authenticatecustomer.php'); ?>

<?php
    extract($_POST);
        try {
            require($phppath.'callable/connection.php');
            $db->beginTransaction();

            $oneprepareorder=$db->prepare("SELECT branchid, userid, type FROM orders WHERE orderid=?");
            $oneprepareorder->execute(array($selectedorderid));
            $oneorder=$oneprepareorder->fetch(PDO::FETCH_ASSOC);

            $oneprepareitems=$db->prepare("SELECT itemid, quantity FROM orderitems WHERE orderid=?");
            $oneprepareitems->execute(array($selectedorderid));
            $oneitems=$oneprepareitems->fetchAll(PDO::FETCH_ASSOC);

            $transactorder=$db->prepare("INSERT INTO orders (branchid, userid, status, type, payment, stamp) VALUES (?, ?, 'Pending', ?, 'cash', SYSDATE())");
            $transactorder->execute(array($oneorder['branchid'], $oneorder['userid'], $oneorder['type']));
            $orderidinsert=$db->lastInsertId();

            $transactitems=$db->prepare("INSERT INTO orderitems (orderid, itemid, quantity) VALUES (?, ?, ?)");
            foreach ($oneitems as $item)
                $transactitems->execute(array($orderidinsert, $item['itemid'], $item['quantity']));

            if ($oneorder['type']=='Delivery') {
                $oneprepareaddress=$db->prepare("SELECT addressid FROM orderaddress WHERE orderid=?");
                $oneprepareaddress->execute(array($selectedorderid));
                $oneaddress=$oneprepareaddress->fetch(PDO::FETCH_ASSOC);
                $transactaddress=$db->prepare("INSERT INTO orderaddress (orderid, addressid) VALUES (?, ?)");
                $transactaddress->execute(array($orderidinsert, $oneaddress['addressid']));
            }
        $db->commit();
        $db=null;
    } catch (PDOException $e) {
        $db->rollback();
        echo "Error occured!";
        die($e->getMessage());
    }
    header("location:".$htmlpath."callable/customer/pastorders.php")
?>
