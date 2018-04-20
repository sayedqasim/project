<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>

<!DOCTYPE html>
<html lang="en">

<!-- Head -->
<?php require($head); ?>

<body>

<!-- Navigation -->
<?php require($navigation); ?>

<!-- Page Content -->
<?php
    try {
        require($phppath.'callable/connection.php');
        $prepq=$db->prepare("SELECT * FROM users WHERE usertype='manager'");
        $prepq->execute();
        $db=null;
        $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        echo "Error occured!";
        die($e->getMessage());
    }
?>
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Manager Management</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Manager Management</li>
    </ol>
    <?php

        foreach ($rowq as $row) {
?>

            <div class='row' >
                <div style='text-align:center;' class='col-md-2' >
                    <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['profilepicture'];; ?>" alt=''>
                </div>
                <div class='col-md-8' >
                    <table style='margin: auto;'>

                        <tr><td>Name:</td><td><?php echo $row['name']; ?></td></tr>
                        <tr><td>Email:</td><td><?php echo $row['email']; ?></td></tr>
                        <tr><td>Phone:</td><td><?php echo $row['phone']; ?></td></tr>
                    </table>
                </div>
                <div style='text-align:center;' class='col-md-2' >
                    <form method='POST'>
                        <input type='hidden' value='$uid' ?>
                        <button formaction="<?php echo $htmlpath.'callable/admin/editmanager.php' ?>" class='btn btn-primary' type='submit' name='edit'>Edit</button>
                        <button formaction="<?php echo $htmlpath.'callable/admin/deletemanager.php' ?>" class='btn btn-primary' type='submit' name='delete'>Delete</button>
                    </form>
                </div>
            </div>
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
