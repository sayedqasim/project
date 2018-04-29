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
    <h1 class="mt-4 mb-3">Your Cart</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Cart</li>
    </ol>
    <hr/>

    <?php
    extract($_POST);
    if(isset($deleteitem)){
      if(isset($_SESSION['cart']) && $_SESSION['cart']!=null){
      foreach ($_SESSION['cart'] as $item) {
        if(!($item['rid']==$restaurantidx && $item['iid']==$itemidx)){
          $newarr[]=$item;
        }
      }
        if(isset($newarr)){
        $_SESSION['cart']=$newarr; //update cart with new item list without the deleted one.
        }
        else {
          $_SESSION['cart']=null; //no items left in cart.
        }
    }
  }
        $ordertotal = 0;
        if(isset($_SESSION['cart'])){ //cart exists
          foreach ($_SESSION['cart'] as $key => $item) {
              $r=$item['rid']; $i=$item['iid']; $q=$item['qty']; $p=$item['price'];
                  try {
                      require($phppath.'callable/connection.php');
                      $prepq=$db->prepare("SELECT * FROM items WHERE restaurantid LIKE ? AND itemid LIKE ?");
                      $prepq->execute(array("%$r%","%$i%"));
                      $prepq->execute();
                      $db=null;
                      $row=$prepq->fetchAll(PDO::FETCH_ASSOC);
                  } catch (PDOException $e) {
                      echo "Error occured!";
                      die($e->getMessage());
                  } ?>
                      <div class='row' >
                        <div style='text-align:center;' class='col-md-2' >
                            <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row[0]['image']; ?>" alt=''>
                          </div>
                          <div class='col-md-6' >
                              <table >
                                <tr><td><b><?php echo $row[0]['title']; ?></b></td></tr>
                                <tr><td><?php echo $row[0]['description']; ?></td></tr>
                              <tr><td><?php echo "Quantity: <b>".$q."</b>"; ?></td></tr>
                              </table>
                          </div>
                          <div style='text-align:center; font-size:20; margin-bottom: 0px' class="col-md-2" > <b>
                            <?php $total=$p*$q; $ordertotal+=$total; ?>
                             BD<?php echo $total; ?> </b>
                          </div>
                          <div style='text-align:center; margin-bottom:0px margin-top:0px' class='col-md-2' >
                              <form>
                                  <input type='hidden' name='restaurantidx' value="<?php echo $row [0]['restaurantid'] ?>">
                                  <input type='hidden' name='itemidx' value="<?php echo $row[0]['itemid'] ?>">
                                  <button style="margin-top:5px;"  formmethod="POST" formaction="<?php echo $htmlpath.'callable/customer/viewCart.php'; ?>" class='btn btn-primary' type='submit' name='deleteitem' value="<?php echo $row['itemid'] ?>">Delete</button>
                              </form>
                          </div>
                      </div> <hr />

  <?php
                  }
                  ?>
                 <div style='text-align:right; font-size:20;' class:'col-mg-2' > <b>
                   <form><?php try {
                       require($phppath.'callable/connection.php');
                       $prepq=$db->prepare("SELECT * FROM restaurants WHERE restaurantid LIKE ?");
                       $xa=$_SESSION['cart'][0]['rid'];
                       $prepq->execute(array("%$xa%"));
                       $prepq->execute();
                       $db=null;
                       $row=$prepq->fetchAll(PDO::FETCH_ASSOC);
                   } catch (PDOException $e) {
                       echo "Error occured!";
                       die($e->getMessage());
                   } if($row[0]['DeliveryType']=="PD") {?>
                     <input type="radio" style=" margin-left:0px;margin-right:2px;  font-size:14;" class='btn btn-primary' name="deltype" value="P">Pickup </input>
                     <input type="radio" style=" margin-left:5px;margin-right:2px; font-size:14;" class='btn btn-primary' name="deltype" value="DP">Delivery </input>
                   <?php } else { ?>
                      <input type="radio" style=" margin-left:0px;margin-right:2px;  font-size:14;" class='btn btn-primary' name="deltype" value="P">Pickup </input>
                   <?php }?>
                     <text-align style="margin-left:20px; margin-right:10px;">
                     <?php echo "Order Total: BD".$ordertotal; ?> </b> </text-align>
                     <button style="margin-left:0px; font-size:14;"  formmethod="POST" formaction="<?php echo $htmlpath.'callable/customer/checkout.php'; ?>" class='btn btn-primary' type='submit' name='deleteitem' value="checkout">Proceed to checkout</button>
                   </form>

                  </div>
                  <br/>
        <?php
                }
        else { //cart does not exist
          echo "<h2 class='mt-4 mb-3'>Your cart is empty.</h2>";
        }


?>
<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
