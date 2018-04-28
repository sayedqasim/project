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

<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Menu</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'callable/customer/browseRestaurants.php';?>">Restaurants</a>
    </li>
    <li class="breadcrumb-item active">Menu</li>
    </ol>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search Menu .." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
        </form>
    </div>
    <br/>

    <?php
        $searchparameter="";
        extract($_POST);
        if (isset($restaurantid))
            $_SESSION['restaurantid']=$restaurantid;




        try {
            require($phppath.'callable/connection.php');
            $prepq=$db->prepare("SELECT * FROM items WHERE (restaurantid=?) AND (title LIKE ? OR description LIKE ? OR type LIKE ?)");
            $prepq->execute(array($_SESSION['restaurantid'],"%$searchparameter%","%$searchparameter%","%$searchparameter%"));
            $db=null;
            $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error occured!";
            die($e->getMessage());
        }

        foreach ($rowq as $row) {
            if (isset($itemid))
                if ($row['itemid']==$itemid)
                    if (isset($_SESSION['cart'][$row['restaurantid']][$itemid]))
                        $_SESSION['cart'][$row['restaurantid']][$itemid]+=$qty;
                    else
                        $_SESSION['cart'][$row['restaurantid']][$itemid]=$qty;

            ?>
            <div class='row' >
                <div style='text-align:center;' class='col-md-2' >
                    <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['image']; ?>" alt=''>
                </div>
                <div class='col-md-7' >
                    <table>
                      <tr><td><b><?php echo $row['title']; ?></b></td></tr>
                      <tr><td><?php echo $row['description']; ?></td></tr>
                      <tr><td><?php echo $row['price']; ?> BD</td></tr>
                    </table>
                </div>
                <div style='text-align:center;' class='col-md-3' >
                  <form method="POST">
                      <table style="margin:auto;">
                          <tr>
                              <td><input style="width: 50px;  margin-right: 5px; margin-top:10px;" type='number' name='qty' value="1"></input></td>
                              <td><button style="margin-top:10px;" class='btn btn-primary' type='submit' name='itemid' value="<?php echo $row['itemid'] ?>">Add</button></td>
                          </tr>
                      </table>
                  </form>
              </div>
            </div>
            <hr/>
            <br/>
    <?php
          }

    ?>
</div>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
