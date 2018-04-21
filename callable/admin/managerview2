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
<div class="container">
    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Manager Management</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Manager Management</li>
    </ol>
    <div style="text-align:center;">
        <a href="<?php echo $htmlpath.'callable/admin/addmanager.php' ?>" ><button style="width:75%;" class="btn btn-primary">Add Manager</button></a>
    </div>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search Managers.." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary btn-xs">Go</button></a>
        </form>
    </div>
    <?php
        extract($_POST);
        if (isset($searchparameter)) {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM users WHERE name LIKE ? OR email like ? OR phone LIKE ?");
                $prepq->execute(array("%$searchparameter%","%$searchparameter%","%$searchparameter%"));
                $db=null;
                $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }

            foreach ($rowq as $row) {
              if ($row['usertype']=='manager') {
                ?>
                <div class='row' >
                    <div style='text-align:center;' class='col-md-2' >
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['profilepicture']; ?>" alt=''>
                    </div>
                    <div class='col-md-8' >
                        <table style='margin: auto;'>
                            <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                            <tr><td><b>Email:</b></td><td><?php echo $row['email']; ?></td></tr>
                            <tr><td><b>Phone:</b></td><td><?php echo $row['phone']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-2' >
                        <form method='POST'>
                            <input type='hidden' name='mid' value="<?php echo $row['userid'] ?>">
                            <button style="margin-top:5px;" formaction="<?php echo $htmlpath.'callable/admin/editmanager.php' ?>" class='btn btn-primary' value="<?php echo $row['userid'] ?>" type='submit' name='edit'>Edit</button>
                            <button style="margin-top:5px;" formaction="<?php echo $htmlpath.'callable/admin/deletemanager.php' ?>" class='btn btn-primary' value="<?php echo $row['userid'] ?>" type='submit' name='delete'>Delete</button>
                        </form>
                    </div>
                </div>
                <br/>
    <?php
            }
          }
        }
        ?>
    <hr/>
    <?php
        if (isset($viewall)) {
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM users WHERE usertype='manager'");
                $prepq->execute();
                $db=null;
                $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            foreach ($rowq as $row) {
                ?>
                <div class='row' >
                    <div style='text-align:center;' class='col-md-2' >
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['profilepicture']; ?>" alt=''>
                    </div>
                    <div class='col-md-8' >
                        <table style='margin: auto;'>
                            <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                            <tr><td><b>Email:</b></td><td><?php echo $row['email']; ?></td></tr>
                            <tr><td><b>Phone:</b></td><td><?php echo $row['phone']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-2' >
                        <form method='POST'>
                            <input type='hidden' value='$uid' ?>
                            <button style="margin-top:5px;" formaction="<?php echo $htmlpath.'callable/admin/editmanager.php' ?>" class='btn btn-primary' type='submit' name='edit'>Edit</button>
                            <button style="margin-top:5px;" formaction="<?php echo $htmlpath.'callable/admin/deletemanager.php' ?>" class='btn btn-primary' type='submit' name='delete'>Delete</button>
                        </form>
                    </div>
                </div>
                <br/>
    <?php
            }
        } else {
            ?>
            <div style="text-align:center;">
                <form method="post">
                    <button type="submit" style="width:75%;" class="btn btn-primary" name="viewall">View All Managers</button>
                </form>
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
