<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/manager/authenticatemanager.php"); ?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<body>

<!-- Navigation -->
<?php require($navigation); ?>


<?php
    $searchparameter="";
    extract($_POST);
    if (isset($restaurantid))
        $_SESSION['restaurantid']=$restaurantid;
    try {
        require($phppath.'callable/connection.php');
        $prepqr=$db->prepare("SELECT * FROM restaurants WHERE restaurantid=?");
        $prepqr->execute(array($_SESSION['restaurantid']));
        $rowqr=$prepqr->fetchAll(PDO::FETCH_ASSOC);

        $prepq=$db->prepare("SELECT * FROM branches WHERE (address LIKE ? OR phone like ?) AND (restaurantid=?)");
        $prepq->execute(array("%$searchparameter%","%$searchparameter%",$_SESSION['restaurantid']));
        $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
        $db=null;
    } catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
?>
<!-- Page Content -->
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Branches</h1>
    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="<?php echo $htmlpath.'callable/manager/restaurants.php';?>">Restaurants</a>
    </li>
    <li class="breadcrumb-item active">Branches</li>
    </ol>
    <div style='text-align:center;'>
        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$rowqr[0]['logo']; ?>" alt=''>
    </div>
    <div>
        <table style="margin:auto;">
            <tr><td><b>Name:</b></td><td><?php echo $rowqr[0]['name']; ?></td></tr>
            <tr><td><b>Description:</b></td><td><?php echo $rowqr[0]['description']; ?></td></tr>
        </table>
    </div>
    <br/>
    <div style="text-align:center;">
        <a href="<?php echo $htmlpath.'callable/manager/addBranch.php' ?>" ><button style="width:75%;" class="btn btn-primary">Add Branch</button></a>
    </div>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search Branches.." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
        </form>
    </div>
    <br/>
    <?php
        if (count($rowq)<=0) {
            echo "<div style='color:red; text-align:center; font-size: 12px;'>No branches found.</div>";
        }
        else {
        foreach ($rowq as $row) {
            $explodedaddress=explode(':', $row['address']);
            $area=$explodedaddress[0];
            $block=$explodedaddress[1];
            $road=$explodedaddress[2];
            $building=$explodedaddress[3];
            $row['address'] = $area . ', Block: ' . $block . ', Road: ' . $road . ', Building: ' . $building . '.';
    ?>
            <div class='row'>
                <div class='col-md-9' >
                    <table>
                        <tr><td><b>Address:</b></td><td><?php echo $row['address']; ?></td></tr>
                        <tr><td><b>Phone:</b></td><td><?php echo $row['phone']; ?></td></tr>
                    </table>
                </div>
                <div style='text-align:right;' class='col-md-3' >
                    <form>
                        <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/manager/editBranch.php' ?>" class='btn btn-primary' type='submit' name='branchid' value="<?php echo $row['branchid'] ?>">Edit</button>
                        <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/manager/deleteBranch.php' ?>" class='btn btn-primary' type='submit' name='branchid' value="<?php echo $row['branchid'] ?>">Delete</button>
                    </form>
                </div>
            </div>
    <?php
        echo "<hr/>";
        }
        }
    ?>
</div>
<br/>

<!-- Footer -->
<?php require($footer); ?>

<!-- Bootstrap core JavaScript -->
<?php require($jsbs); ?>

</body>

</html>
