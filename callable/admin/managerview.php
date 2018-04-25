<!-- Modular Require -->
<?php require('C:\xampp\htdocs\project\callable\modularRequire.php'); ?>
<?php require($phppath."callable/admin/authenticateadmin.php"); ?>

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
    <h1 class="mt-4 mb-3">Managers</h1>

    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo $htmlpath.'index.php';?>">Home</a>
    </li>
        <li class="breadcrumb-item active">Managers</li>
    </ol>
    <div style="text-align:center;">
        <a href="<?php echo $htmlpath.'callable/admin/addmanager.php' ?>" ><button style="width:75%;" class="btn btn-primary">Add Manager</button></a>
    </div>
    <hr/>
    <div style="text-align:center;">
        <form method="post">
            <input type="text" style="width:64%;" placeholder="Search Managers.." name="searchparameter">
            <button type="submit" style="width:10%;" class="btn btn-primary">Go</button></a>
        </form>
    </div>
    <?php
        $searchparameter="";
        extract($_POST);
            try {
                require($phppath.'callable/connection.php');
                $prepq=$db->prepare("SELECT * FROM users WHERE (name LIKE ? OR email like ? OR phone LIKE ?) AND (usertype='manager')");
                $prepq->execute(array("%$searchparameter%","%$searchparameter%","%$searchparameter%"));
                $db=null;
                $rowq=$prepq->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error occured!";
                die($e->getMessage());
            }
            if (count($rowq)<=0) {
                echo "<div style='color:red; text-align:center; font-size: 12px;'>No search results found.</div>";
            }
            foreach ($rowq as $row) {

                ?>
                <br/>
                <div class='row' >
                    <div style='text-align:center;' class='col-md-2' >
                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$row['profilepicture']; ?>" alt=''>
                    </div>
                    <div class='col-md-5' >
                        <table >
                            <tr><td><b>Name:</b></td><td><?php echo $row['name']; ?></td></tr>
                            <tr><td><b>Email:</b></td><td><?php echo $row['email']; ?></td></tr>
                            <tr><td><b>Phone:</b></td><td><?php echo $row['phone']; ?></td></tr>
                        </table>
                    </div>
                    <div style='text-align:center;' class='col-md-5' >
                        <form>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/appointManager.php' ?>" class='btn btn-primary' type='submit' name='managerid' value="<?php echo $row['userid'] ?>">Appoint</button>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/editmanager.php' ?>" class='btn btn-primary' type='submit' name='managerid' value="<?php echo $row['userid'] ?>">Edit</button>
                            <button style="margin-top:5px;" formmethod="POST" formaction="<?php echo $htmlpath.'callable/admin/deletemanager.php' ?>" class='btn btn-primary' type='submit' name='managerid' value="<?php echo $row['userid'] ?>">Delete</button>
                        </form>
                    </div>
                </div>
                        <?php
                            try {
                                require($phppath.'callable/connection.php');
                                $preps=$db->prepare("SELECT * FROM restaurants WHERE restaurantid IN (SELECT restaurantid FROM restaurantmanagers WHERE managerid=?)");
                                $preps->execute(array($row['userid']));
                                $db=null;
                                $rows=$preps->fetchAll(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {
                                echo "Error occured!";
                                die($e->getMessage());
                            }
                            if (count($rows)<=0) {
                                echo "<div style='color:red; text-align:center; font-size: 12px;'>No restaurant appointed yet.</div>";
                            }
                            else {
                                echo "<div style='color:red; text-align:center; font-size: 12px;'>Is appointed to:</div>";
                            foreach ($rows as $r) {
                        ?>

                                <div class='row' >
                                    <div style='text-align:center;' class='col-md-2' >
                                        <img style='margin: auto;' class='img-fluid rounded mb-3 mb-md-0' width='100' height='100' src="<?php echo $htmlpath.$r['logo']; ?>" alt=''>
                                    </div>
                                    <div class='col-md-8' >
                                        <table >
                                            <tr><td><b>Name:</b></td><td><?php echo $r['name']; ?></td></tr>
                                            <tr><td><b>Description:</b></td><td><?php echo $r['description']; ?></td></tr>
                                        </table>
                                    </div>
                                </div>
                    <?php
                            }
                    ?>
    <?php
            }
            echo "<hr/>";
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
