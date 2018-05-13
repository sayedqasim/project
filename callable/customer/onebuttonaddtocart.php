<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath.'callable/customer/authenticatecustomer.php'); ?>

<?php
    extract($_POST);
    try {
        require($phppath.'callable/connection.php');

        $onepreparerestaurantid=$db->prepare("SELECT branches.restaurantid FROM orders, branches WHERE orders.branchid=branches.branchid AND orders.orderid=?");
        $onepreparerestaurantid->execute(array($selectedorderid));
        $onerestaurantid=$onepreparerestaurantid->fetch(PDO::FETCH_ASSOC);

        $oneprepareitems=$db->prepare("SELECT itemid, quantity FROM orderitems WHERE orderid=?");
        $oneprepareitems->execute(array($selectedorderid));
        $oneitems=$oneprepareitems->fetchAll(PDO::FETCH_ASSOC);

        if (isset($_SESSION['cart'][$onerestaurantid['restaurantid']]))
            unset($_SESSION['cart'][$onerestaurantid['restaurantid']]);

        foreach ($oneitems as $item)
            $_SESSION['cart'][$onerestaurantid['restaurantid']]['items'][$item['itemid']]=$item['quantity'];

        $_SESSION['cart'][$onerestaurantid['restaurantid']]['selectedbranch']="0:Not Selected";
        $_SESSION['cart'][$onerestaurantid['restaurantid']]['selectedaddress']="0:Pickup";

        $db=null;
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
    header("location:".$htmlpath."callable/customer/pastorders.php")
?>
